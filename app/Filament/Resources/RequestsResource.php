<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Employee;
use App\Models\Requests;
use Filament\Forms\Form;
use App\Models\Stationery;
use Filament\Tables\Table;
use App\Models\Request_detail;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use function Laravel\Prompts\select;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextArea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\RequestsExporter;


use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\RequestsResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RequestsResource\RelationManagers;

class RequestsResource extends Resource
{
    protected static ?string $model = Requests::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Stationery';

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-up';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('employee_id')
                    ->label('Karyawan')
                    ->options(Employee::where('div_id', Auth::user()->div_id)->pluck('name', 'id')) // Ambil data divisi
                    ->required()
                    ->searchable(),
                Textarea::make('information')
                    ->required()
                    ->label('Informasi'),
                DateTimePicker::make('submit')
                    ->label('Waktu Pengajuan')
                    ->required(),
                Repeater::make('requestDetails')
                    ->relationship()
                    ->schema([
                        Select::make('stationery_id')
                            ->label('Alat Tulis')
                            ->options(Stationery::where('div_id', Auth::user()->div_id)->pluck('name', 'id')) // Ambil data divisi
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
                TextColumn::make('employee.name')
                    ->searchable()
                    ->label('Nama'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(function ($state) {
                        return match ($state) {
                            'accepted' => 'success',
                            'pending' => 'warning',
                            'rejected' => 'danger',
                            default => 'gray',
                        };
                    })
                    ->icon(function ($state) {
                        return match ($state) {
                            'accepted' => 'heroicon-m-check-circle',
                            'pending' => 'heroicon-m-clock',
                            'rejected' => 'heroicon-m-check-badge',
                            default => 'heroicon-m-exclamation-circle',
                        };
                    }),
                TextColumn::make('submit')
                ->date()
                ->sortable()
                ->searchable()
                ->toggleable(),
            ])
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(RequestsExporter::class)
            ])
            ->emptyStateHeading('Tidak Ada Permintaan')
            ->emptyStateDescription('')
            ->emptyStateIcon('heroicon-o-document-arrow-up')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Ajukan Permintaan')
                    ->url(route('request.create'))
                    ->icon('heroicon-m-plus')
                    ->button(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                SelectFilter::make('status')
                ->label('Status')
                ->multiple()
                ->options([
                    'Accepted' => 'Accepted',
                    'Pending' => 'Pending',
                    'Rejected' => 'Rejected',
                ]),
                Filter::make('submit')
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
                Tables\Actions\EditAction::make()
                    ->hidden(
                        fn(Requests $record): bool =>
                        !is_null($record->deleted_at) || $record->status !== 'pending'
                    ),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Action::make('approval')
                    ->url(fn($record) => static::getUrl('detail', ['record' => $record]))
                    ->authorize('detail', Requests::class),
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
            'detail' => Pages\RequestDetail::route('/{record}/detail'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->whereHas('employee', function (Builder $query) {
                $query->where('div_id', Auth::user()->div_id);
            });
    }
}
