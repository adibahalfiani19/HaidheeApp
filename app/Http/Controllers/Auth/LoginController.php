<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');  
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Ambil credential untuk autentikasi
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Regenerate session untuk keamanan
            $request->session()->regenerate();

            // Cek peran pengguna setelah berhasil login
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('home');
            }
        }

        // Jika login gagal, kembali ke halaman login dengan pesan error
        return redirect()->back()->with('loginError', 'Email atau password salah');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Regenerate session untuk memastikan data sesi yang ada benar-benar dihapus
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Arahkan kembali ke halaman landing page setelah logout
        return redirect()->route('landing'); // Sesuaikan nama route jika perlu
    }
}

