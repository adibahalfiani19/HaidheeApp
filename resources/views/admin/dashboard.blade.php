<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Haidhee</title>
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}" sizes="20x20">

    <style>
        /* Sticky header with transparency on scroll */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: rgba(255, 255, 255, 1);
            backdrop-filter: blur(10px);
            z-index: 1000;
            transition: background-color 0.3s ease;
        }

        /* Add padding to body to avoid content hidden under fixed header */
        body {
            margin: 0; /* Menghapus jarak default */
            padding-top: 60px; /* Menambahkan jarak untuk menyesuaikan dengan header yang fixed */
        }

        /* Ensure sidebar background remains opaque */
        #sidebar {
            position: fixed;
            top: 0;
            right: 0;
            width: 16rem; /* 64px * 4 = 256px */
            height: 100%;
            background-color: #ffffff !important; /* Solid background */
            z-index: 1001; /* Ensures sidebar appears above header */
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }

        #userDropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background-color: #ffffff;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
            width: 220px;
            z-index: 9999;
            padding: 0.5rem 0;
        }

        /* Styling untuk header (nama dan email pengguna) */
        #userDropdown .dropdown-header {
            padding: 5px 20px;
            font-weight: bold;
            color: #333;
        }

        #userDropdown .dropdown-subheader {
            padding: 0 20px 12px 20px;
            color: #777;
            font-size: 0.85em;
        }

        /* Divider yang lebih halus */
        #userDropdown .divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 4px 0;
        }

        #userDropdown a,
        #userDropdown button {
            display: block;
            padding: 0.5rem 1rem;
            text-align: left;
            color: #4a4a4a;
            width: 100%; /* Pastikan elemen memenuhi lebar kontainer */
            box-sizing: border-box; /* Pastikan padding tidak menyebabkan overflow */
        }

        #userDropdown a:hover,
        #userDropdown button:hover {
            background-color: #f5f5f5;
            color: #333333;
        }

        /* Hover states untuk mempertahankan tampilan */
        .relative:hover #userDropdown,
        #userDropdown:hover {
            display: block;
        }

        #userDropdown .border-b {
            border-bottom: 1px solid #e5e7eb;
        }
    </style>
</head>

@include('auth.login')
@include('auth.register')

<body class="font-poppins bg-gray-100">

<!-- Header -->
<header class="bg-white shadow-md py-6 rounded-b-3xl">
    <div class="container mx-auto flex justify-between items-center px-6 md:px-14">
        <!-- Logo Haidhee -->
        <h2 class="text-3xl font-bold">
            <span class="text-[#5F7E78]">Haidh</span><span class="text-[#D4BE83]">ee</span>
        </h2>
        
        <!-- Burger Icon for Small Screens -->
        <div class="lg:hidden">
            <button id="burger-menu" class="text-gray-600 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Navigation for Large Screens -->
        <!-- resources/views/components/navbar.blade.php -->
        <nav class="hidden lg:flex items-center space-x-6 ml-auto">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-[#D4BE83]">Beranda</a>
            <a href="{{ route('article.index') }}" class="text-gray-600 hover:text-[#D4BE83]">Artikel</a>
            <a href="{{ route('admin.users') }}" class="text-gray-600 hover:text-[#D4BE83]">Pengguna</a>

            <div class="flex items-center space-x-3">
                @guest
                    <a href="#" id="loginButton" class="text-[#5F7E78] font-semibold border border-[#5F7E78] px-4 py-2 rounded-xl hover:bg-teal-50">Masuk</a>
                    <a href="#" id="registerButton" class="bg-[#5F7E78] text-white font-semibold px-4 py-2 rounded-xl hover:bg-[#4e6863]">Daftar</a>
                @endguest
                @auth
                <div class="relative">
                    <button id="userAvatar" class="w-10 h-10 bg-[#5F7E78] text-white rounded-full flex items-center justify-center font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </button>
                    <!-- Dropdown Menu -->
                    <div id="userDropdown">
                        <div class="dropdown-header">{{ Auth::user()->name }}</div>
                        <div class="dropdown-subheader">{{ Auth::user()->email }}</div>
                        <div class="divider"></div>
                        <form action="{{ route('logout') }}" method="POST" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100">Keluar</button>
                        </form>                        
                    </div>                    
                </div>                                           
                @endauth
            </div>
        </nav>

    </div>
</header>

<!-- Sidebar Navigation for Small Screens -->
<div id="sidebar">
    <div class="p-4 flex justify-between items-center border-b">
        <button id="close-sidebar" class="text-gray-600 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <ul class="p-4 space-y-4">
        <li><a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-[#D4BE83]">Beranda</a></li>
        <li><a href="{{ route('article.index') }}" class="text-gray-600 hover:text-[#D4BE83]">Artikel</a></li>
        <li><a href="{{ route('admin.users') }}" class="text-gray-600 hover:text-[#D4BE83]">Pengguna</a></li>
    </ul>

    <!-- Sidebar Content for Authenticated Users -->
    <div class="p-4">
        @guest
            <a href="#" id="loginButtonSidebar" class="block text-center text-[#5F7E78] border border-[#5F7E78] px-4 py-2 rounded-xl hover:bg-teal-50 mb-4">Masuk</a>
            <a href="#" id="registerButtonSidebar" class="block text-center bg-[#5F7E78] text-white px-4 py-2 rounded-xl hover:bg-[#4e6863]">Daftar</a>
        @endguest

        @auth
            <!-- User Profile Section for Authenticated User -->
            <div class="flex items-center space-x-4 mb-4">
                <!-- Avatar -->
                <div class="w-10 h-10 bg-[#5F7E78] text-white rounded-full flex items-center justify-center font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <!-- Name and Email -->
                <div>
                    <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                    <p class="text-sm text-gray-600">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <!-- Logout Button -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="block w-full text-center bg-[#5F7E78] text-white px-4 py-2 rounded-xl hover:bg-[#4e6863]">Keluar</button>
            </form>
        @endauth
    </div>
</div>

<!-- Hero Section -->
<section class="hero-section py-16 bg-gradient-to-b from-[#5F7E78] to-[#B8D6D0] rounded-b-3xl">
    <div class="container mx-auto px-14 flex flex-col lg:flex-row items-center">
        <!-- Text Content -->
        <div class="lg:w-1/2 text-center lg:text-left">
            @auth
                <p class="text-lg font-bold text-white mb-6">
                    Assalamu'alaikum, <span style="color: #D4BE83;">{{ Auth::user()->name }}</span>!
                </p>
            @endauth
            <h1 class="text-4xl font-bold text-white mb-4">Catat dan Kelola Menstruasimu dengan Lebih Mudah</h1>
            <p class="text-white">Selamat datang di Haidee, aplikasi pencatatan menstruasi yang dirancang khusus untuk wanita muslimah. Dengan fitur-fitur unggulan yang kami tawarkan, Haidee akan menjadi teman setia dalam menjalani siklus menstruasimu dengan lebih teratur dan sesuai dengan tuntunan Islam.</p>
        </div>

        <!-- Image Content -->
        <div class="lg:w-1/2 mt-10 lg:mt-0 flex justify-center lg:justify-end">
            <img src="{{ asset('images/Landing-Haidhee.png') }}" alt="Ilustrasi Pencatatan Menstruasi" class="w-full max-w-xs lg:max-w-md hidden lg:block ">
        </div>
    </div>
</section>

<!-- Feature Section -->
<section class="feature-section text-center py-16">
    <div class="container mx-auto px-6 md:px-14">
        <h2 class="text-2xl font-bold text-gray-800 mb-12">Apa saja yang bisa kalian dapatkan di Haidhee?</h2>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-24">
            <div class="flex flex-col items-center">
                <img src="{{ asset('images/Calendar.png') }}" alt="Feature 1" class="mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Pencatatan Menstruasi Secara Efisien dan Akurat</h3>
                <p class="text-gray-600">Pantau siklus menstruasi Anda dengan mudah melalui kalender interaktif kami. Dengan Haidhee, Anda dapat mencatat dan melacak periode menstruasi Anda secara akurat, membantu Anda merencanakan kegiatan sehari-hari dengan lebih baik.</p>
            </div>
            <div class="flex flex-col items-center">
                <img src="{{ asset('images/Identifikasi.png') }}" alt="Feature 2" class="mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Identifikasi Jenis Darah Sesuai Aturan Islam</h3>
                <p class="text-gray-600">Haidee membantu Anda mengidentifikasi jenis darah yang keluar, apakah itu menstruasi (haid) atau istihadah, sesuai dengan panduan Islam. Dengan ini, Anda dapat memahami dan mengelola keadaan kesehatan Anda dengan lebih baik.</p>
            </div>
            <div class="flex flex-col items-center">
                <img src="{{ asset('images/Reminder.png') }}" alt="Feature 3" class="mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Pengingat Salat Setelah Masa Menstruasi</h3>
                <p class="text-gray-600">Tidak perlu khawatir tentang ketinggalan salat setelah masa haid berakhir. Haidhee memberikan informasi dan notifikasi tentang salat yang perlu diqada setelah menstruasi selesai, berdasarkan catatan Anda. Jadikan ibadah salat lebih teratur dan bermakna dengan bantuan Haidhee.</p>
            </div>
        </div>
    </div>
</section>

<!-- Article Section -->
<section class="article-section py-16 bg-[#B8D6D0] rounded-t-3xl rounded-b-3xl">
    <div class="container mx-auto px-6 md:px-14">
        <h2 class="text-xl font-bold text-center text-gray-800 mb-12">Pelajari lebih banyak lagi tentang menstruasi dalam Islam!</h2>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-24">
            <div class="bg-white p-8 rounded-2xl shadow-md h-[450px]">
                <img src="https://via.placeholder.com/300" alt="Article 1" class="rounded-lg mb-6 w-full h-[250px] object-cover">
                <h3 class="text-md font-semibold text-gray-800 mb-4">Apa itu Hemofilia? Ini Penyebab, Gejala, dan Penanganannya</h3>
            </div>
            <div class="bg-white p-8 rounded-2xl shadow-md h-[450px]">
                <img src="https://via.placeholder.com/300" alt="Article 2" class="rounded-lg mb-6 w-full h-[250px] object-cover">
                <h3 class="text-md font-semibold text-gray-800 mb-4">Mengenal Penyakit Hemofilia, Kala Perdarahan Sulit Dihentikan</h3>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-md h-[450px]">
                <img src="https://via.placeholder.com/300" alt="Article 3" class="rounded-lg mb-6 w-full h-[250px] object-cover">
                <h3 class="text-md font-semibold text-gray-800 mb-4">Hemofilia, Kenali Penyebab hingga Jenisnya</h3>
            </div>
        </div>
        <div class="flex justify-center mt-12">
            <a href="#" class="bg-white text-[#5F7E78] px-6 py-3 rounded-xl font-semibold hover:bg-[#4e6863] transition duration-300 shadow-md">Lihat Lebih Banyak</a>
        </div>
    </div>
</section>


<!-- Footer -->
<footer class="footer py-8 bg-white">
    <div class="container mx-auto text-center text-black font-medium">
        <p>&copy; 2024 Haidhee. All rights reserved.</p>
    </div>
</footer>

<script>
    // JavaScript to control the sidebar
    document.getElementById('burger-menu').addEventListener('click', function() {
        document.getElementById('sidebar').style.transform = 'translateX(0)';
    });

    document.getElementById('close-sidebar').addEventListener('click', function() {
        document.getElementById('sidebar').style.transform = 'translateX(100%)';
    });

    // JavaScript to make header transparent when scrolling
    window.addEventListener('scroll', function() {
        const header = document.querySelector('header');
        if (window.scrollY > 50) {
            header.style.backgroundColor = 'rgba(255, 255, 255, 0.5)';
        } else {
            header.style.backgroundColor = 'rgba(255, 255, 255, 1)';
        }
    });

    // JavaScript to control Login and Register Modals
    document.getElementById('loginButton').addEventListener('click', function() {
        document.getElementById('loginModal').classList.remove('hidden');
    });

    document.getElementById('registerButton').addEventListener('click', function() {
        document.getElementById('registerModal').classList.remove('hidden');
    });

    document.getElementById('tryNowButton').addEventListener('click', function() {
        document.getElementById('registerModal').classList.remove('hidden');
    });

    document.getElementById('closeLogin').addEventListener('click', function() {
        document.getElementById('loginModal').classList.add('hidden');
    });

    document.getElementById('closeRegister').addEventListener('click', function() {
        document.getElementById('registerModal').classList.add('hidden');
    });

    document.addEventListener('DOMContentLoaded', function() {
        @if(session('registrationSuccess'))
            document.getElementById('loginModal').classList.remove('hidden');
        @endif
    });
</script>
</body>
</html>
