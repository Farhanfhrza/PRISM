<?php

namespace App\Filament\Resources\RequestsResource\Pages;

use App\Models\Employee;
use Filament\Forms\Form;
use App\Models\Stationery;
use App\Models\Transaction;
use Filament\Actions\Action;
use App\Models\Request_detail;
use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\RequestsResource;
use Filament\Forms\Components\DateTimePicker;

class RequestDetail extends ViewRecord
{
    protected static string $resource = RequestsResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('employee_id')
                    ->label('Karyawan')
                    ->options(Employee::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->disabled(),

                Textarea::make('information')
                    ->required()
                    ->label('Informasi')
                    ->disabled(),

                DateTimePicker::make('submit')
                    ->required()
                    ->label('Waktu Pengajuan')
                    ->format('d/m/Y')
                    ->disabled(),

                Repeater::make('requestDetails')
                    ->relationship()
                    ->schema([
                        Select::make('stationery_id')
                            ->label('Alat Tulis')
                            ->options(Stationery::all()->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->reactive()
                            ->disabled()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $stationery = Stationery::find($state);
                                if ($stationery) {
                                    $set('stock', $stationery->stock);
                                }
                            }),

                        TextInput::make('amount')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->disabled(),
                    ])
                    ->disabled(),
            ]);
    }

    protected function getActions(): array
    {
        return [
            Action::make('accept')
                ->label('Accept Request')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->action(function () {
                    $record = $this->record;

                    // Update status menjadi accepted
                    $record->status = 'accepted';
                    $record->approved = now();
                    $record->save();

                    // Notifikasi berhasil
                    Notification::make()
                        ->title('Request Accepted')
                        ->success()
                        ->send();

                    // Redirect atau refresh
                    $this->redirect(RequestsResource::getUrl('index'));
                })
                ->visible(fn($record) => $record->status === 'pending'),

            Action::make('reject')
                ->label('Reject Request')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->action(function () {
                    $record = $this->record;

                    $stationeries = Request_detail::where('request_id', $this->record->id)->get()->toArray();

                    foreach ($stationeries as $stationery) {
                        $stok = Stationery::find($stationery['stationery_id']);
                        $stok->stock += $stationery['amount']; // Kurangi stok
                        $stok->save();
                    }

                    // Update status menjadi rejected
                    $record->status = 'rejected';
                    $record->approved = now();
                    $record->save();

                    $user = Filament::auth()->user();
                    $stationeries = Request_detail::where('request_id', $this->record->id)->get();
                    foreach ($stationeries as $stationery) {
                        $stok = Stationery::find($stationery->stationery_id);
                        $stok->stock -= $stationery->amount;
                        $stok->save();

                        Transaction::create([
                            'user_id' => $user?->id,
                            'stationery_id' => $stok->id,
                            'transaction_type' => 'In',
                            'div_id' => $user?->div_id,
                            'amount' => $stationery->amount,
                            'description' => "Pengguna {$user->name} membatalkan request {$stok->name} sebanyak {$stationery->amount} {$stok->unit}",
                            'source_type' => 'Request',
                            'source_id' => $this->record->id,
                            'created_at' => now(),
                        ]);
                    }

                    // Notifikasi berhasil
                    Notification::make()
                        ->title('Request Rejected')
                        ->danger()
                        ->send();

                    // Redirect atau refresh
                    $this->redirect(RequestsResource::getUrl('index'));
                })
                ->visible(fn($record) => $record->status === 'pending')
        ];
    }
}
