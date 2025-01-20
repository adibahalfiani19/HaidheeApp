<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PrayerTimeController;
use App\Http\Controllers\MenstruationController;
use App\Http\Controllers\PanduanController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProfileController;
use App\Models\Menstruation;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {
        return view('user.home'); // Buat view baru untuk halaman khusus user
    })->name('home');
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/article', [ArticleController::class, 'index'])->name('article.index');
    Route::get('/article/create', [ArticleController::class, 'create'])->name('article.create');
    Route::post('/article', [ArticleController::class, 'store'])->name('article.store');
    Route::get('/article/{article}/edit', [ArticleController::class, 'edit'])->name('article.edit');
    Route::put('/article/{id}', [ArticleController::class, 'update'])->name('article.update');
    Route::delete('/article/{article}', [ArticleController::class, 'destroy'])->name('article.destroy');
});

// Route untuk menampilkan daftar artikel bagi user dan guest
Route::get('/articles', [ArticleController::class, 'showArticles'])->name('articles');
Route::get('/articles/{id}', [ArticleController::class, 'showArticleDetail'])->name('article.detail');
Route::get('/', [ArticleController::class, 'getLatestArticles'])->name('landing');

Route::get('/home', [ArticleController::class, 'getLatestArticlesForHome'])->name('home')->middleware('auth');
// Route::get('/admin', [ArticleController::class, 'getLatestArticlesForAdmin'])->name('admin')->middleware('auth');

Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users')->middleware('auth');
Route::delete('/admin/users/{id}', [AdminController::class, 'destroyUser'])->name('user.destroy');

// Pencatatan Menstruasi (User)
Route::middleware('auth')->prefix('calendar')->group(function () {
    Route::get('/', [MenstruationController::class, 'create'])->name('calendar.create'); // Halaman Create Baru
    Route::get('/{id}/edit', [MenstruationController::class, 'edit'])->name('calendar.edit'); // Halaman Edit Data
    Route::post('/', [MenstruationController::class, 'store'])->name('calendar.store'); // Simpan Data Baru
    Route::put('/{id}', [MenstruationController::class, 'update'])->name('calendar.update'); // Update Data yang Ada
    Route::delete('/delete/{id}', [MenstruationController::class, 'destroy'])->name('calendar.destroy');
});

Route::get('/menstruation/detail/{id}', [MenstruationController::class, 'getDetail'])->name('menstruation.detail');
Route::post('/menstruation/update-qada-salat/{id}', [MenstruationController::class, 'updateQadaSalat']);

Route::get('/riwayat-menstruasi', [MenstruationController::class, 'showHistory'])->name('riwayat.menstruasi')->middleware('auth');
Route::get('/home', [MenstruationController::class, 'home'])->name('home');

//API
Route::get('/prayer-times', [PrayerTimeController::class, 'getPrayerTimes'])->name('prayer.times');

Route::get('/panduan-haid', [PanduanController::class, 'haid'])->name('panduan.haid');
Route::get('/panduan-qadha', [PanduanController::class, 'qadha'])->name('panduan.qadha');


//LOGIN GOOGLE
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);


Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index'); // Halaman profil
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update'); // Perbarui profil
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); // Hapus akun
});