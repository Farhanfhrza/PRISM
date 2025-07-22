<?php

namespace App\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExportDemandDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $data = DB::table('request_details')
            ->join('requests', 'requests.id', '=', 'request_details.request_id')
            ->join('stationeries', 'stationeries.id', '=', 'request_details.stationery_id')
            ->join('employees', 'employees.id', '=', 'requests.employee_id')
            ->selectRaw('requests.submit as date, stationeries.name as stationery_name, employees.div_id as division, request_details.amount')
            ->get();

        $csvPath = storage_path('app/demand_training.csv');
        $handle = fopen($csvPath, 'w');
        fputcsv($handle, ['date', 'stationery_name', 'division', 'amount']);

        foreach ($data as $row) {
            fputcsv($handle, [
                $row->date,
                $row->stationery_name,
                $row->division,
                $row->amount,
            ]);
        }

        fclose($handle);
    }
}
