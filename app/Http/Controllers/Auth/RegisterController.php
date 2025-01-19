<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller; 
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        //Validasi input dari pengguna
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:5',
            // 'whatsapp_number' => 'required|string|max:20',
        ]);
        

        // Buat pengguna baru di database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // 'whatsapp_number' => $request->whatsapp_number,
            'role' => 'user', // Default role adalah user biasa
        ]);

        // Event pendaftaran berhasil
        event(new Registered($user));

        // Redirect ke landing dengan login modal terbuka
        return redirect('/')->with('registrationSuccess', true);
    }
}