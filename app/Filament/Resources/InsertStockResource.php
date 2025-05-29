<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Stationery;
use Filament\Tables\Table;
use App\Models\InsertStock;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\InsertStockResource\Pages;
use App\Filament\Resources\InsertStockResource\RelationManagers;

class InsertStockResource extends Resource
{
    protected static ?string $model = InsertStock::class;

    protected static ?string $navigationGroup = 'Stock';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('stationery_id')
                    ->label('Stationery')
                    ->options(fn() => Stationery::where('div_id', Auth::user()->div_id)->pluck('name', 'id')) // Ambil data divisi
                    ->required()
                    ->searchable(),
                TextInput::make('amount')
                    ->numeric()
                    ->required()
                    ->minValue(1),
                DateTimePicker::make('inserted_at')
                    ->label('Waktu Input')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('stationery.name')->label('Stationery'),
                TextColumn::make('amount')->label('Jumlah'),
                TextColumn::make('inserted_at')
                ->label('Waktu Input')
                ->date()
                ->sortable()
                ->searchable(),
                TextColumn::make('insertedBy.name')
                ->label('Diinput Oleh'),
            ])
            ->filters([
                Filter::make('inserted_at')
                    ->form([
                        DatePicker::make('from')->label('Dari Tanggal'),
                        DatePicker::make('to')->label('Sampai Tanggal')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date)
                            )
                            ->when(
                                $data['to'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date)
                            );
                    })
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListInsertStocks::route('/'),
            'create' => Pages\CreateInsertStock::route('/create'),
            'edit' => Pages\EditInsertStock::route('/{record}/edit'),
        ];
    }
}
