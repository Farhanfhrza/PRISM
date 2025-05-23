<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InsertStockResource\Pages;
use App\Filament\Resources\InsertStockResource\RelationManagers;
use App\Models\InsertStock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
