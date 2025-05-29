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
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $email = $credentials['email']; 
            $user = Auth::user();

            // Catat aktivitas login
            Activity_log::create([
                'user_id' => $user->id,
                'activity_type' => 'login',
                'activity_category' => 'authentication',
                'description' => "User '{$user->name}' dengan email '{$email}' telah login",
            ]);

            return redirect()->intended('/');
        }

        return back()->with('loginError', 'Login Failed!');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        // Catat aktivitas logout
        Activity_log::create([
            'user_id' => $user->id,
            'activity_type' => 'logout',
            'activity_category' => 'authentication',
            'description' => "User '{$user->name}' dengan email '{$user->email}' telah logout",
        ]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
