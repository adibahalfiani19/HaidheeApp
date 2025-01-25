<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Ambil data pengguna yang login
        return view('profile.index', compact('user'));
    }

    /**
     * Perbarui data profil pengguna.
     */
    public function update(Request $request)
    {
        
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255', // Validasi untuk nama
            'password' => [
                'nullable', // Password opsional
                'string',
                'min:6',
                'regex:/[A-Z]/', // Harus ada huruf besar
                'regex:/\d/' // Harus ada angka
            ],
            'whatsapp_number' => 'nullable|numeric|digits_between:10,15',
        ], [
            'password.regex' => 'Password harus mengandung setidaknya satu huruf besar dan satu angka.',
            'password.min' => 'Password minimal harus memiliki 6 karakter.',
            'whatsapp_number.numeric' => 'Nomor WhatsApp harus berupa angka.',
            'whatsapp_number.digits_between' => 'Nomor WhatsApp harus antara 10 hingga 15 digit.',
        ]);

        // Ambil data pengguna yang sedang login
        $user = Auth::user();

        // Update nama
        $user->name = $request->name;

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Update nomor WhatsApp jika diisi
        if ($request->filled('whatsapp_number')) {
            $user->whatsapp_number = $request->whatsapp_number;
        } else {
            $user->whatsapp_number = null; // Jika kosong, set ke null
        }
        
        /** @var \App\Models\User $user */

        $user->save(); // Simpan perubahan ke database

        return back()->with('changeSuccess', 'Profil berhasil diperbarui.');
    }

    public function destroy(Request $request)
    {
        $user = Auth::user(); // Ambil pengguna yang sedang login

        // Logout pengguna sebelum menghapus akun
        Auth::logout();

        /** @var \App\Models\User $user */
        // Hapus akun dari database
        $user->delete();

        // Invalidate sesi pengguna
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke halaman landing page dengan pesan sukses
        return redirect('/')->with('success', 'Akun Anda berhasil dihapus.');
    }  

}