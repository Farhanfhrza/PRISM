<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Requests;
use App\Models\Request_detail;
use App\Models\Employee;
use App\Models\Activity_log;
use App\Models\Stationery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function index()
    {
        $requests = Requests::with(['employee', 'request_detail.stationery'])->get();
        return view('request.index', compact('requests'));
    }

    public function create()
    {
        return view('request.create');
    }

    public function getName(Request $request)
    {
        $query = $request->get('query');
        $division = Auth::user()->div_id;

        // Cari data berdasarkan input  
        $results = Employee::where('div_id', $division) // Pertama, filter berdasarkan div_id  
            ->where('name', 'LIKE', "%{$query}%")
            ->select('id', 'name') // Pastikan untuk memilih ID  
            ->get();

        return response()->json($results);
    }

    public function getStationery(Request $request)
    {
        $query = $request->get('query');
        $division = Auth::user()->div_id;

        // Cari data berdasarkan input  
        $results = Stationery::where('div_id', $division) // Pertama, filter berdasarkan div_id  
            ->where('name', 'LIKE', "%{$query}%")
            ->select('name', 'stock') // Select name and stock fields  
            ->get();

        return response()->json($results);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {
            // Validasi dasar  
            $validated = $request->validate([
                'employee_id' => 'required|integer|exists:employees,id',
                'items' => 'required|json',
                'description' => 'required|string|max:500'
            ]);

            // Decode items dengan error handling  
            // $items = json_decode($request->items, true, 512, JSON_THROW_ON_ERROR);

            $items = json_decode($request->items, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \JsonException('Invalid JSON format');
            }
            // dd($items);

            // Create main request  
            $requestData = [
                'employee_id' => (int)$validated['employee_id'],
                'submit' => now(),
                'information' => $validated['description']
            ];

            $requestMain = Requests::create($requestData);

            foreach ($items as $item) {
                $stationery = Stationery::where('name', trim($item['stationery']))
                    ->where('div_id', $validated['employee_id'])
                    ->first();

                // Kurangi stok  
                $stationery->decrement('stock', $item['amount']);

                Request_detail::create([
                    'request_id' => $requestMain->id,
                    'stationery_id' => $stationery->id,
                    'amount' => (int)$item['amount']
                ]);
            }

            DB::commit();

            return redirect()->route('requests')->with('success', 'Permintaan berhasil diajukan');
        } catch (\JsonException $e) {
            DB::rollBack();
        }
    }

    public function edit($id)
    {
        $request = Requests::with(['employee', 'request_detail.stationery'])->findOrFail($id);

        // Prepare items data in the same format as create form expects  
        $items = $request->details->map(function ($detail) {
            return [
                'stationery' => $detail->stationery->name,
                'amount' => $detail->amount
            ];
        });

        $request->items = json_encode($items);

        return view('request.edit', compact('request'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            // Validasi dasar  
            $validated = $request->validate([
                'employee_id' => 'required|integer|exists:employees,id',
                'items' => 'required|json',
                'description' => 'required|string|max:500'
            ]);

            // Decode items with error handling  
            $items = json_decode($request->items, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \JsonException('Invalid JSON format');
            }

            // Get the existing request  
            $requestMain = Requests::findOrFail($id);

            // First, revert all previous stock deductions  
            foreach ($requestMain->details as $detail) {
                $detail->stationery->increment('stock', $detail->amount);
            }

            // Delete all existing details  
            $requestMain->details()->delete();

            // Update main request  
            $requestMain->update([
                'employee_id' => (int)$validated['employee_id'],
                'information' => $validated['description'],
                'updated_at' => now()
            ]);

            // Process new items  
            foreach ($items as $item) {
                $stationery = Stationery::where('name', trim($item['stationery']))
                    ->where('div_id', $validated['employee_id'])
                    ->firstOrFail();

                // Deduct stock for new amounts  
                $stationery->decrement('stock', $item['amount']);

                Request_detail::create([
                    'request_id' => $requestMain->id,
                    'stationery_id' => $stationery->id,
                    'amount' => (int)$item['amount']
                ]);
            }

            DB::commit();

            return redirect()->route('requests')->with('success', 'Permintaan berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui permintaan: ' . $e->getMessage());
        }
    }
}
