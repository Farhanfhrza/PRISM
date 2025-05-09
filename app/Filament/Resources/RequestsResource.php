<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RequestsResource\Pages;
use App\Filament\Resources\RequestsResource\RelationManagers;
use App\Models\Requests;
use App\Models\Employee;
use App\Models\Stationery;
use App\Models\Request_detail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextArea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\TextColumn;


use function Laravel\Prompts\select;

class RequestsResource extends Resource
{
    protected static ?string $model = Requests::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-up';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('employee_id')
                    ->label('Karyawan')
                    ->options(Employee::all()->pluck('name', 'id')) // Ambil data divisi
                    ->required()
                    ->searchable(),
                Textarea::make('information')
                    ->required()
                    ->label('Informasi'),
                Repeater::make('requestDetails')
                    ->relationship()
                    ->schema([
                        Select::make('stationery_id')
                            ->label('Alat Tulis')
                            ->options(Stationery::all()->pluck('name', 'id')) // Ambil data divisi
                            ->required()
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $stationery = Stationery::find($state);
                                if ($stationery) {
                                    $set('stock', $stationery->stock);
                                }
                            })
                            ->rules([
                                function ($get, $set) {
                                    return function (string $attribute, $value, $fail) use ($get, $set) {
                                        $currentItems = $get('../../requestDetails'); // Ambil semua item dalam Repeater
                                        $duplicates = array_filter($currentItems, function ($item) use ($value) {
                                            return isset($item['stationery_id']) && $item['stationery_id'] == $value;
                                        });

                                        if (count($duplicates) > 1) {
                                            $fail('Alat tulis ini sudah dipilih sebelumnya.');
                                        }
                                    };
                                }
                            ]),
                        TextInput::make('amount')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->rules([
                                'required',
                                'numeric',
                                'min:1',
                                function ($get) {
                                    return function (string $attribute, $value, $fail) use ($get) {
                                        $stationeryId = $get('stationery_id');
                                        $stationery = Stationery::find($stationeryId);
    
                                        if ($stationery) {
                                            // Hitung stok tersedia (stok saat ini + stok yang sedang di-request)
                                            $currentRequestId = $get('../../id');
                                            $requestedAmount = 0;
    
                                            if ($currentRequestId) {
                                                $requestedAmount = Request_detail::where('request_id', $currentRequestId)
                                                    ->where('stationery_id', $stationeryId)
                                                    ->sum('amount');
                                            }
    
                                            $availableStock = $stationery->stock + $requestedAmount;
    
                                            if ($value > $availableStock) {
                                                $fail("Jumlah melebihi stok tersedia (Stok: {$availableStock})");
                                            }
                                        }
                                    };
                                }
                            ]),
                        TextInput::make('stock')->label('Stok Tersedia')->disabled(),
                    ]),
                Hidden::make('submit')->default(now())
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('requestDetails.stationery_id')
                    ->label('Request Details')
                    ->formatStateUsing(function ($record) {
                        return view('filament.columns.request-details', [
                            'details' => $record->requestDetails,
                        ]);
                    }),
                TextColumn::make('employee.name')->label('Nama'),
                TextColumn::make('status')
                    ->label('Status')
                    ->color(function ($state) {
                        return match ($state) {
                            'approved' => 'success',
                            'pending' => 'warning',
                            'rejected' => 'danger',
                            default => 'gray',
                        };
                    }),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->hidden(fn (Requests $record): bool => !is_null($record->deleted_at)),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRequests::route('/'),
            'create' => Pages\CreateRequests::route('/create'),
            'edit' => Pages\EditRequests::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
