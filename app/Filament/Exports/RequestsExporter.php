<?php

namespace App\Filament\Exports;

use App\Models\Requests;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;

class RequestsExporter extends Exporter
{
    protected static ?string $model = Requests::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('employee.name')
                ->label('Nama'),
            ExportColumn::make('status')
                ->label('Status'),
            ExportColumn::make('request_details')
                ->label('Request Details')
                ->formatStateUsing(function ($record) {
                    return $record->requestDetails->map(function ($detail) {
                        return "{$detail->stationery->name} (Qty: {$detail->amount})";
                    })->implode('\n');
                }),
            ExportColumn::make('created_at')
                ->label('Created At')
                ->formatStateUsing(fn($state) => $state?->format('Y-m-d H:i:s')),
        ];
    }

    public static function getFormComponents(): array
    {
        return [
            DatePicker::make('start_date')
                ->label('Start Date'),
            DatePicker::make('end_date')
                ->label('End Date'),
            Select::make('status')
                ->label('Request Status')
                ->options([
                    'pending' => 'Pending',
                    'accepted' => 'Accepted',
                    'rejected' => 'Rejected',
                ])
                ->multiple(),
        ];
    }

    public function resolveRecord(array $data): ?Requests
    {
        $query = Requests::query();

        // Apply date range filter if dates are provided
        if (!empty($data['start_date']) && !empty($data['end_date'])) {
            $query->whereBetween('created_at', [$data['start_date'], $data['end_date']]);
        }

        // Apply status filter if status is selected
        if (!empty($data['status'])) {
            $query->whereIn('status', $data['status']);
        }

        // Return null if no records match
        return $query->first();
    }

    public function getRecords(array $data): iterable
    {
        // Fetch all records matching the filter criteria
        $query = Requests::with(['employee', 'requestDetails.stationery']);

        // Apply date range filter if dates are provided
        if (!empty($data['start_date']) && !empty($data['end_date'])) {
            $query->whereBetween('created_at', [$data['start_date'], $data['end_date']]);
        }

        // Apply status filter if status is selected
        if (!empty($data['status'])) {
            $query->whereIn('status', $data['status']);
        }

        // Return the query for Filament to handle
        return $query->cursor();
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your requests export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
