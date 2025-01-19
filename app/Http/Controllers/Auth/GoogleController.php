<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect pengguna ke Google untuk autentikasi.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback dari Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cari user berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Jika user tidak ditemukan, buat user baru
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => bcrypt('default-password'), // Password default
                    'role' => 'user', // Role default
                ]);
            }

            // Login pengguna
            Auth::login($user);

            // Redirect ke halaman sesuai role
            return redirect()->route($user->role === 'admin' ? 'admin.dashboard' : 'home');
        } catch (\Exception $e) {
            // Tangani error
            return redirect('/')->with('error', 'Login dengan Google gagal.');
        }
    }

}
