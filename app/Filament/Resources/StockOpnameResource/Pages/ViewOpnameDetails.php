<?php

namespace App\Filament\Resources\StockOpnameResource\Pages;

use Filament\Tables;
use App\Models\Stationery;
use App\Models\Transaction;
use App\Models\OpnameDetail;
use Filament\Actions\Action;
use App\Data\OpnameDetailData;
use Filament\Facades\Filament;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Response;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Filament\Actions\Imports\ImportAction;
use App\Filament\Resources\StockOpnameResource;

class ViewOpnameDetails extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string $resource = \App\Filament\Resources\StockOpnameResource::class;

    protected static string $view = 'filament.resources.stock-opname-resource.pages.view-opname-details';

    public $record;

    public function mount($record): void
    {
        $this->record = \App\Models\StockOpname::findOrFail($record);
    }

    protected function getTableQuery(): Builder
    {
        return OpnameDetail::where('opname_id', $this->record->id);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('stationery.name')->label('Stationery'),
            TextColumn::make('system_stock'),
            TextColumn::make('actual_stock'),
            TextColumn::make('difference'),
            TextColumn::make('note'),
        ];
    }

    public function getHeaderActions(): array
    {
        $user = Filament::auth()->user();
        $opname = $this->record;
        $actions = [];

        // Tombol Approve (hanya untuk Ketua Divisi dan status Draft)
        if ($opname->opname_status === 'Draft' && ($user->hasRole('Ketua Divisi') || $user->hasRole('Super Admin'))) {
            $actions[] = Action::make('approve')
                ->label('Setujui Opname')
                ->icon('heroicon-o-check')
                ->color('primary')
                ->requiresConfirmation()
                ->action(function () use ($opname, $user) {
                    $opname->update([
                        'opname_status' => 'Approved',
                        'approved_by' => $user->id,
                    ]);
                    Notification::make()
                        ->title('Opname Disetujui')
                        ->success()
                        ->send();
                    // Filament::notify('success', 'Status Opname disetujui.');
                });
        }
        
        if ($opname->opname_status === 'Draft' && ($user->hasRole('Ketua Divisi') || $user->hasRole('Super Admin'))) {
            $actions[] = Action::make('reject')
                ->label('Tolak Opname')
                ->icon('heroicon-o-x-circle')
                ->color('warning')
                ->requiresConfirmation()
                ->action(function () use ($opname, $user) {
                    $opname->update([
                        'opname_status' => 'Cancelled',
                        'approved_by' => $user->id,
                    ]);
                    Notification::make()
                        ->title('Opname Ditolak')
                        ->success()
                        ->send();
                    // Filament::notify('success', 'Status Opname disetujui.');
                });
        }

        // Tombol Export Template (hanya untuk Staff Gudang saat Draft)
        if ($opname->opname_status === 'Draft' && ($user->hasRole('Staff Gudang') || $user->hasRole('Super Admin'))) {
            $actions[] = Action::make('export-template')
                ->label('Export Template Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(fn() => $this->exportTemplate())
                ->requiresConfirmation();
        }

        // Tombol Import Excel (hanya untuk Staff Gudang saat Draft)
        if ($opname->opname_status === 'Draft' && ($user->hasRole('Staff Gudang') || $user->hasRole('Super Admin'))) {
            $actions[] = Action::make('import-excel')
                ->label('Import Opname Detail')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    \Filament\Forms\Components\FileUpload::make('file')
                        ->label('File Excel')
                        ->disk('local')
                        ->directory('imports')
                        ->required(),
                ])
                ->action(function (array $data) use ($opname) {
                    $filePath = storage_path('app/' . $data['file']);

                    // Hapus data lama
                    OpnameDetail::where('opname_id', $opname->id)->delete();

                    $rows = \Spatie\SimpleExcel\SimpleExcelReader::create($filePath)->getRows();

                    foreach ($rows as $row) {
                        OpnameDetail::create([
                            'opname_id' => $opname->id,
                            'stationery_id' => $row['stationery_id'],
                            'system_stock' => $row['system_stock'],
                            'actual_stock' => $row['actual_stock'],
                            'difference' => $row['difference'],
                            'note' => $row['note'] ?? null,
                        ]);
                    }

                    // Filament::notify('success', 'Data berhasil diimpor.');
                });
        }

        // Tombol Apply Stock (hanya jika status Approved dan role Staff Gudang / Ketua Divisi)
        if (
            $opname->opname_status === 'Approved' &&
            ($user->hasRole('Staff Gudang') || $user->hasRole('Ketua Divisi') || $user->hasRole('Super Admin'))
        ) {
            $actions[] = Action::make('apply-actual-stock')
                ->label('Terapkan Actual Stock')
                ->icon('heroicon-o-check-circle')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Terapkan Actual Stock')
                ->modalSubheading('Ini akan mengubah stok sesuai actual stock dan menyelesaikan opname.')
                ->action(function () use ($opname, $user) {
                    $details = OpnameDetail::where('opname_id', $opname->id)->get();

                    foreach ($details as $detail) {
                        $stationery = Stationery::find($detail->stationery_id);
                        if ($stationery) {
                            $oldStock = $stationery->stock;
                            $stationery->stock = $detail->actual_stock;
                            $stationery->save();

                            Transaction::create([
                                'user_id' => $user->id,
                                'stationery_id' => $stationery->id,
                                'transaction_type' => $detail->actual_stock > $oldStock ? 'In' : 'Out',
                                'div_id' => $user->div_id,
                                'amount' => abs($detail->actual_stock - $oldStock),
                                'description' => "Stock opname menyesuaikan stok {$stationery->name} dari {$oldStock} ke {$detail->actual_stock}",
                                'source_type' => 'Stock Opname',
                                'source_id' => $opname->id,
                                'created_at' => now(),
                            ]);
                        }
                    }

                    $opname->update(['opname_status' => 'Completed']);
                    // Filament::notify('success', 'Stok berhasil diterapkan, opname selesai.');
                });
        }

        return $actions;
    }

    protected function exportTemplate()
    {
        $user = Filament::auth()->user();
        $fileName = "template-opname-{$this->record->id}.xlsx";
        $tempPath = storage_path("app/{$fileName}");

        $stationeries = Stationery::where('div_id', $user->div_id)->get();

        $rows = $stationeries->map(function ($item) {
            return [
                'stationery_id' => $item->id,
                'name' => $item->name,
                'system_stock' => $item->stock,
                'actual_stock' => '',
                'difference' => '',
                'note' => '',
            ];
        });

        SimpleExcelWriter::create($tempPath)
            ->addRows($rows->toArray());

        return response()->download($tempPath)->deleteFileAfterSend(true);
    }
}
