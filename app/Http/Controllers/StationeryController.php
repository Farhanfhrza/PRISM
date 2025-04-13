<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Stationery;
use App\Models\Activity_log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StationeryController extends Controller
{
    public function index()
    {
        $stationeries = Stationery::all();
        return view('stationery.index', compact('stationeries'));
    }

    public function create()
    {
        return view('stationery.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'category' => 'required',
            'stock' => 'required',
            'description' => 'required',
        ]);

        $validatedData['div_id'] = Auth::user()->div_id;

        Stationery::create($validatedData);

        $username = Auth::user()->username;
        $name = $validatedData['name'];
        $activity = Activity_log::create([
            'user_id' => Auth::id(),
            'activity_type' => 'create',
            'activity_category' => 'stationery',
            'description' => "User '$username' telah menambah alat tulis baru '$name'",
        ]);

        return redirect()->route('stationeries')->with('success', 'Record created successfully.');
    }
}
