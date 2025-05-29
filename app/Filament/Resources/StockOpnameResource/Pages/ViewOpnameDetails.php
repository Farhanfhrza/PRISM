<?php

namespace App\Filament\Resources\StockOpnameResource\Pages;

use App\Filament\Resources\StockOpnameResource;
use Filament\Resources\Pages\Page;
use App\Models\OpnameDetail;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use App\Models\Stationery;
use Illuminate\Support\Facades\Response;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Filament\Actions\Imports\ImportAction;
use App\Data\OpnameDetailData;
use Filament\Forms\Components\FileUpload;
use Spatie\SimpleExcel\SimpleExcelReader;
use Filament\Notifications\Notification;

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
        return [
            Action::make('export-template')
                ->label('Export Template Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(fn() => $this->exportTemplate())
                ->requiresConfirmation()
                ->color('success'),

            Action::make('import-excel')
                ->label('Import Opname Detail')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    FileUpload::make('file')
                        ->label('File Excel')
                        ->disk('local') // default Laravel storage/app
                        ->directory('imports')
                        ->required()
                    // ->acceptedFileTypes(['.csv', '.xlsx'])
                    ,
                ])
                ->action(function (array $data): void {
                    $filePath = storage_path('app/' . $data['file']);

                    $rows = SimpleExcelReader::create($filePath)->getRows();

                    OpnameDetail::where('opname_id', $this->record->id)->delete();

                    foreach ($rows as $row) {
                        OpnameDetail::create([
                            'opname_id' => $this->record->id,
                            'stationery_id' => $row['stationery_id'],
                            'system_stock' => $row['system_stock'],
                            'actual_stock' => $row['actual_stock'],
                            'difference' => $row['difference'],
                            'note' => $row['note'] ?? null,
                        ]);
                    }

                    Notification::make()
                        ->title('Import Berhasil')
                        ->body('Data berhasil diimpor dan data lama telah dihapus.')
                        ->success()
                        ->send();;
                }),
        ];
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
