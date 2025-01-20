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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:6',
                'regex:/[A-Z]/', // Harus ada huruf besar
                'regex:/\d/' // Harus ada angka
            ],
        ], [
            'password.regex' => 'Passwordmu harus mengandung huruf besar dan angka.',
            'password.min' => 'Password minimal harus memiliki 6 karakter.',
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

        // Redirect ke landing page dengan modal login terbuka
        return redirect('/')
            ->with('registrationSuccess', true)
            ->with('alertMessage', 'Registrasi berhasil! Silakan login.');
    }
}