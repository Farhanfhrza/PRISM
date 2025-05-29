<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Division;
use Filament\Forms\Form;
use App\Models\Stationery;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextArea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StationeryResource\Pages;
use App\Filament\Resources\StationeryResource\RelationManagers;

class StationeryResource extends Resource
{
    protected static ?string $model = Stationery::class;

    protected static ?string $navigationGroup = 'Stationery';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-paper-clip';

    protected static bool $globallySearchable = true;

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'category'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('category')->required(),
                TextInput::make('stock')
                    ->numeric()
                    ->required()
                    ->disabledOn('edit'),
                TextInput::make('unit')
                    ->required()
                    ->disabledOn('edit'),
                Select::make('div_id')
                    ->label('Divisi')
                    ->options(Division::all()->pluck('name', 'id')) // Ambil data divisi
                    ->required()
                    ->searchable()
                    ->disabledOn('edit'),
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
                TextColumn::make('id')
                ->label('ID'),
                TextColumn::make('name')
                ->searchable()
                ->label('Nama'),
                TextColumn::make('category')
                ->searchable()
                ->label('Kategori'),
                TextColumn::make('stock')->numeric()->label('Stok'),
                TextColumn::make('unit')->numeric()->label('Satuan'),
            ])
            ->query(
                Stationery::query()->where('div_id', Auth::user()->div_id)
            )
            ->filters([
                SelectFilter::make('category')
                ->label('Kategori')
                ->multiple()
                ->options([
                    'Stationery' => 'Stationery',
                    'Electronic' => 'Electronic',
                    'Utility' => 'Utility',
                ]),
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
