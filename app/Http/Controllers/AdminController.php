<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function users()
    {
        $users = User::where('role', 'user')->paginate(10); // Sesuaikan jumlah per halaman
        $userCount = User::where('role', 'user')->count(); // Hitung jumlah pengguna
        return view('admin.users.index', compact('users', 'userCount'));
    }
    
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus.');
    }
}
