<?php

namespace App\Http\Controllers\API;

use App\Models\Requests;
use App\Models\Request_detail;
use App\Models\Stationery;
use App\Models\Activity_log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class RequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Requests::with(['employee', 'employee.division']);

        if ($user->role !== 'Super Admin') {
            $query->whereHas('employee', fn($q) => $q->where('div_id', $user->div_id));
        }

        $data = $query->orderBy('submit', 'desc')->get();

        return response()->json(['data' => $data]);
    }

    public function show($id)
    {
        $req = Requests::with(['employee', 'requestDetails.stationery', 'employee.division'])->findOrFail($id);
        return response()->json(['data' => $req]);
    }

    public function destroy($id)
    {
        $req = Requests::findOrFail($id);
        $req->details()->delete();
        $req->delete();
        return response()->json(['message' => 'Request deleted.']);
    }

    public function approve($id)
    {
        $req = Requests::findOrFail($id);
        $req->update(['status' => 'Accepted', 'approved' => now()]);
        return response()->json(['message' => 'Request approved.']);
    }

    public function reject($id)
    {
        $req = Requests::findOrFail($id);
        $req->update(['status' => 'Denied', 'approved' => now()]);
        return response()->json(['message' => 'Request rejected.']);
    }


    public function store(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'submit' => 'required|date',
            'information' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.stationery_id' => 'required|exists:stationeries,id',
            'items.*.amount' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $req = Requests::create([
                'employee_id' => $validated['employee_id'],
                'submit' => $validated['submit'],
                'status' => 'Pending',
                'initiated_by' => $user->id,
                'information' => $validated['information'],
            ]);

            foreach ($validated['items'] as $item) {
                $stationery = Stationery::findOrFail($item['stationery_id']);

                if ($item['amount'] > $stationery->stock) {
                    throw new \Exception("Stok tidak cukup untuk {$stationery->name}");
                }

                Request_detail::create([
                    'request_id' => $req->id,
                    'stationery_id' => $item['stationery_id'],
                    'amount' => $item['amount'],
                ]);
            }

            // Activity_log::create([
            //     'user_id' => $user->id,
            //     'activity_type' => 'CREATE',
            //     'activity_category' => 'REQUEST',
            //     'description' => "Membuat permintaan ATK #{$req->id}",
            //     'timestamp' => now(),
            // ]);

            DB::commit();
            return response()->json(['success' => true, 'data' => $req]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
