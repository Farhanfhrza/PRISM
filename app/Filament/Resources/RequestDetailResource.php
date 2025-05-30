<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RequestDetailResource\Pages;
use App\Filament\Resources\RequestDetailResource\RelationManagers;
use App\Models\Request_detail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RequestDetailResource extends Resource
{
    protected static ?string $model = Request_detail::class;

    protected static ?string $navigationGroup = 'Stationery';

    protected static ?int $navigationSort = 3;
    
    protected static ?string $navigationLabel = 'Request Details';
    
    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';

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
            'index' => Pages\ListRequestDetails::route('/'),
            'create' => Pages\CreateRequestDetail::route('/create'),
            'edit' => Pages\EditRequestDetail::route('/{record}/edit'),
        ];
    }
}
