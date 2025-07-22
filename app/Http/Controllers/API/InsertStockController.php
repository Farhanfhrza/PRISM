<?php

namespace App\Http\Controllers\API;

use App\Models\Stationery;
use App\Models\InsertStock;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class InsertStockController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $stocks = InsertStock::with(['stationery'])
            ->whereHas('stationery', fn($q) => $q->where('div_id', $user->div_id))
            ->latest()
            ->get();

        return response()->json(['data' => $stocks]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'stationery_id' => 'required|exists:stationeries,id',
            'amount' => 'required|integer|min:1',
            'inserted_at' => 'required|date',
        ]);

        $user = Auth::user();

        $stationery = Stationery::where('id', $request->stationery_id)
            ->where('div_id', $user->div_id)
            ->firstOrFail();

        DB::transaction(function () use ($request, $user, $stationery) {
            $insert = InsertStock::create([
                'stationery_id' => $stationery->id,
                'amount' => $request->amount,
                'inserted_by' => $user->id,
                'inserted_at' => $request->inserted_at,
            ]);
        });

        return response()->json(['message' => 'Stok berhasil ditambahkan'], 200);
    }

    public function destroy($id)
    {
        $user = Auth::user();

        $insert = InsertStock::with('stationery')
            ->where('id', $id)
            ->whereHas('stationery', fn($q) => $q->where('div_id', $user->div_id))
            ->firstOrFail();

        DB::transaction(function () use ($insert, $user) {
            // Transaction::create([
            //     'user_id' => $user->id,
            //     'stationery_id' => $insert->stationery_id,
            //     'transaction_type' => 'Out',
            //     'amount' => $insert->amount,
            //     'source_type' => 'insert_stock_deleted',
            //     'source_id' => $insert->id,
            //     'description' => 'Rollback delete insert stock',
            //     'created_at' => now(),
            //     'div_id' => $user->div_id,
            // ]);

            // $insert->stationery->decrement('stock', $insert->amount);
            $insert->delete();
        });

        return response()->json(['message' => 'Data stok dihapus dan rollback stok berhasil']);
    }

    
}
