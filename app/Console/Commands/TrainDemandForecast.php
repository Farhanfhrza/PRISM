<?php

namespace App\Console\Commands;

use App\Models\DemandForecast;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TrainDemandForecast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forecast:train';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Latih model permintaan alat tulis dan simpan prediksi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("🔄 Ekstraksi data permintaan...");
        $data = DB::table('request_details')
            ->join('requests', 'requests.id', '=', 'request_details.request_id')
            ->join('stationeries', 'stationeries.id', '=', 'request_details.stationery_id')
            ->join('employees', 'employees.id', '=', 'requests.employee_id')
            ->selectRaw("requests.submit as date, stationeries.name as stationery_name, employees.div_id as division, request_details.amount")
            ->get();

        $csvPath = storage_path('app/ml/demand_data.csv');

        $this->info("📦 Simpan data ke $csvPath");
        $fp = fopen($csvPath, 'w');
        fputcsv($fp, ['date', 'stationery_name', 'division', 'amount']);
        foreach ($data as $row) {
            fputcsv($fp, [(string) $row->date, $row->stationery_name, $row->division, $row->amount]);
        }
        fclose($fp);

        $this->info("🚀 Jalankan training...");
        exec("python scripts/train_forecast.py");

        $forecastPath = public_path('forecasts/demand_forecast.csv');

        if (!file_exists($forecastPath)) {
            $this->error("❌ Gagal: file hasil tidak ditemukan.");
            return 1;
        }

        $this->info("📥 Impor hasil prediksi ke database...");
        $handle = fopen($forecastPath, 'r');
        $headers = fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            DemandForecast::updateOrCreate([
                'date' => $row[0],
                'stationery_name' => $row[1],
                'division' => $row[2],
            ], [
                'predicted_demand' => $row[3],
                'lower_bound' => $row[4],
                'upper_bound' => $row[5],
            ]);
        }
        fclose($handle);

        $this->info("✅ Prediksi berhasil disimpan ke DB.");
        return 0;
    }
}
