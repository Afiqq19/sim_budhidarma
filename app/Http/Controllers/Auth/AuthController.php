<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $role = Auth::user()->role;

            // Menggunakan nama rute (route name) jauh lebih aman daripada URL manual
            if ($role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($role === 'bendahara') {
                return redirect()->intended(route('bendahara.dashboard'));
            } elseif ($role === 'wali_kelas') {
                return redirect()->intended(route('walikelas.dashboard')); // Pasti sukses!
            } elseif ($role === 'siswa') {
                return redirect()->intended('/siswa/dashboard');
            } elseif ($role === 'tu') {
                return redirect()->intended(route('tu.dashboard'));
            }

        }

        return back()->with('error', 'Username atau Password salah!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}