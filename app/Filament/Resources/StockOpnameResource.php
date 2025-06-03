<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\StockOpname;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\OpnameDetailResource;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StockOpnameResource\Pages;
use App\Filament\Resources\StockOpnameResource\RelationManagers;

class StockOpnameResource extends Resource
{
    protected static ?string $model = StockOpname::class;

    protected static ?string $navigationGroup = 'Stock';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('description')->required(),
                DateTimePicker::make('opname_date')
                    ->label('Waktu Opname')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('initiator.name')->label('Initiator'),
                TextColumn::make('approver.name')->label('Approver'),
                TextColumn::make('opname_date')->date()
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('opname_status')
                    ->label('Status')
                    ->badge()
                    ->color(function ($state) {
                        return match ($state) {
                            'Draft' => 'warning',
                            'Completed' => 'info',
                            'Approved' => 'success',
                            'Cancelled' => 'danger',
                            default => 'gray',
                        };
                    })
                    ->sortable(),
                TextColumn::make('description')->label('Deskripsi'),
                TextColumn::make('division.name')->label('Divisi'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn(StockOpname $record) => $record->opname_status !== 'Completed'),
                Action::make('Detail')
                    ->url(fn($record) => route('filament.admin.resources.stock-opnames.viewDetails', ['record' => $record]))
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->color('primary'),
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
            'index' => Pages\ListStockOpnames::route('/'),
            'create' => Pages\CreateStockOpname::route('/create'),
            'edit' => Pages\EditStockOpname::route('/{record}/edit'),
            'viewDetails' => \App\Filament\Resources\StockOpnameResource\Pages\ViewOpnameDetails::route('/{record}/details'),
        ];
    }
}
