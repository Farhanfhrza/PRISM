<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Division;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        $divisi = Division::all();
        return view('register', compact('divisi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'role' => 'required',
            'divisi' => 'required',
            'password' => 'required|min:3|max:255',
        ]);

        $user = User::create([
            'username' => $request->username,
            'role' => $request->role,
            'div_id' => $request->divisi,
            'password' => Hash::make($request->password)
        ]);

        return redirect(route('login'))->with('success', 'Akun Berhasil Dibuat!');
    }
}
