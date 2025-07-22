<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OpnameDetailResource\Pages;
use App\Filament\Resources\OpnameDetailResource\RelationManagers;
use App\Models\OpnameDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OpnameDetailResource extends Resource
{
    protected static ?string $model = OpnameDetail::class;

    protected static ?string $navigationGroup = 'Stock';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            'index' => Pages\ListOpnameDetails::route('/'),
            'create' => Pages\CreateOpnameDetail::route('/create'),
            'edit' => Pages\EditOpnameDetail::route('/{record}/edit'),
        ];
    }
}
