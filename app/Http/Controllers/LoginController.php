<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Activity_log;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $username = $credentials['username']; 
            $activity = Activity_log::create([
                'user_id' => Auth::id(),
                'activity_type' => 'login',
                'activity_category' => 'authentication',
                'description' => "User '$username' telah login",
            ]);

            return redirect()->intended('/');
        }

        return back()->with('loginError', 'Login Failed!');
    }

    public function logout(Request $request)
    {
        $username = Auth::user()->username;
        $activity = Activity_log::create([
            'user_id' => Auth::id(),
            'activity_type' => 'logout',
            'activity_category' => 'authentication',
            'description' => "User '$username' telah logout",
        ]);

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
