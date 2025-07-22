<?php

namespace App\Http\Controllers\API;

use App\Models\Stationery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StationeryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Stationery::with('division');

        // Super Admin bisa lihat semua stok
        if (!$user->hasRole('Super Admin')) {
            // Jika bukan super admin, hanya data divisinya sendiri
            $query->where('div_id', $user->div_id);
        }

        $stationery = $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'category' => $item->category,
                'stock' => $item->stock,
                'divisi_name' => $item->division->name ?? null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $stationery
        ]);
    }

    public function getStationeryByDivision()
    {
        $user = Auth::user();
        $stationery = Stationery::where('div_id', $user->div_id)->get();

        return response()->json([
            'success' => true,
            'data' => $stationery,
        ]);
    }
}
