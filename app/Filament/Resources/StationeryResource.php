<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StationeryResource\Pages;
use App\Filament\Resources\StationeryResource\RelationManagers;
use App\Models\Stationery;
use App\Models\Division;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextArea;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class StationeryResource extends Resource
{
    protected static ?string $model = Stationery::class;

    protected static ?string $navigationGroup = 'Stationery';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-paper-clip';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('category')->required(),
                TextInput::make('stock')->numeric()->required(),
                TextInput::make('unit')->required(),
                Select::make('div_id')
                    ->label('Divisi')
                    ->options(Division::all()->pluck('name', 'id')) // Ambil data divisi
                    ->required()
                    ->searchable(), // Agar bisa mencari divisi
                Textarea::make('description')->required(),
            ]);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        // Salin nilai 'stock' ke 'initial_stock' hanya saat membuat data baru
        $data['initial_stock'] = $data['stock'];

        return $data;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('name')->label('Nama'),
                TextColumn::make('category')->label('Kategori'),
                TextColumn::make('stock')->numeric()->label('Stok'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListStationeries::route('/'),
            'create' => Pages\CreateStationery::route('/create'),
            'edit' => Pages\EditStationery::route('/{record}/edit'),
        ];
    }
}
