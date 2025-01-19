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
        return view('profile'); // Mengarahkan ke profile.blade.php
    }

    // public function update(Request $request)
    // {
    //     /** @var \App\Models\User $user */
    //     $user = Auth::user();
    //     if (!$user) {
    //         return response()->json(['success' => false, 'message' => 'Pengguna tidak ditemukan.'], 404);
    //     }
    
    //     $request->validate([
    //         'name' => 'nullable|string|max:255', // Tidak lagi required
    //         'password' => 'nullable|string|min:6',
    //     ]);
    
    //     // if ($request->has('name') && !empty($request->name)) {
    //     //     $user->name = $request->name;
    //     // }
    
    //     // if ($request->has('password') && !empty($request->password)) {
    //     //     $user->password = Hash::make($request->password);
    //     // }

    //     if ($request->filled('name')) {
    //         $user->name = $request->name;
    //     }

    //     if ($request->filled('password')) {
    //         $user->password = Hash::make($request->password);
    //     }

    //         // Simpan perubahan ke database
    //     try {
    //         $user->save(); // Simpan ke database
    //         return response()->json(['success' => true, 'message' => 'Profil berhasil diperbarui.']);
    //     } catch (\Exception $e) {
    //         return response()->json(['success' => false, 'message' => 'Gagal menyimpan perubahan.']);
    //     }
    // }

    public function update(Request $request)
    {
        $user = Auth::user(); // Ambil data pengguna dari sesi
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Pengguna tidak ditemukan.'], 404);
        }

        if (!($user instanceof \App\Models\User)) {
            return response()->json(['success' => false, 'message' => 'Autentikasi gagal. Model User tidak valid.']);
        }
    
        // Validasi input
        $validated = $request->validate([
            'name' => 'nullable|string|max:255', // Nama opsional
            'password' => 'nullable|string|min:6', // Password opsional
        ]);
    
        // Flag untuk mendeteksi perubahan
        $isChanged = false;
    
        // Periksa apakah nama diubah
        if (!empty($validated['name']) && $validated['name'] !== $user->name) {
            $user->name = $validated['name'];
            $isChanged = true; // Tandai bahwa ada perubahan
        }
    
        // Periksa apakah password diubah
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
            $isChanged = true; // Tandai bahwa ada perubahan
        }
    
        // Jika ada perubahan, simpan ke database
        if ($isChanged) {
            $user->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui.',
            ]);
        }
    
        // Jika tidak ada perubahan
        return response()->json([
            'success' => false,
            'message' => 'Tidak ada perubahan yang disimpan.',
        ]);
    }
    
    public function delete()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Pengguna tidak ditemukan.'], 404);
        }

        $user->delete();

        return response()->json(['success' => true]);
    }
}
