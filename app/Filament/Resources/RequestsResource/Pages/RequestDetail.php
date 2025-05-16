<?php

namespace App\Filament\Resources\RequestsResource\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\RequestsResource;
use App\Models\Employee;
use App\Models\Stationery;
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

                    // Update status menjadi rejected
                    $record->status = 'rejected';
                    $record->approved = now();
                    $record->save();

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
