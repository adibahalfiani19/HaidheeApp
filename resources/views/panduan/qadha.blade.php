<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tata Cara Qada Salat - Haidhee</title>
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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

        /* Custom styles */
        .content-container {
            max-width: 960px;
            margin: auto;
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .main-title {
            text-align: center;
            font-size: 2.2rem;
            font-weight: bold;
            color: #D4BE83;
            margin-bottom: 2rem;
        }
        .section-title {
            color: #D4BE83;
            font-size: 1.75rem;
            font-weight: bold;
            margin-top: 2rem;
            margin-bottom: 1rem;
            border-bottom: 2px solid #D4BE83;
        }
        .sub-section-title {
            color: #5F7E78;
            font-size: 1.5rem;
            font-weight: bold;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
        }
        .article-content p, .article-content ul {
            font-size: 1rem;
            color: #333;
            line-height: 1.8;
            margin-bottom: 1rem;
        }
        ul {
            margin-left: 1.5rem;
        }
        strong {
            color: #5F7E78;
            font-weight: bold;
        }
        .back-button {
            display: block;
            margin-top: 2rem;
            text-align: center;
            color: #5F7E78;
            font-weight: bold;
            text-decoration: none;
        }
        .back-button:hover {
            text-decoration: underline;
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
            @guest
            <a href="{{ route('landing') }}" class="text-gray-600 hover:text-[#D4BE83]">Beranda</a>
            <a href="{{ route('articles') }}" class="text-gray-600 hover:text-[#D4BE83]">Artikel</a>
            <a href="#" class="text-gray-600 hover:text-[#D4BE83]">Kalender</a>
            <a href="#" class="text-gray-600 hover:text-[#D4BE83]">Riwayat</a>
            @endguest

            @auth
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-[#D4BE83]">Beranda</a>
            <a href="{{ route('articles') }}" class="text-gray-600 hover:text-[#D4BE83]">Artikel</a>
            <a href="{{ route('calendar.create') }}" class="text-gray-600 hover:text-[#D4BE83]">Kalender</a>
            <a href="{{ route('riwayat.menstruasi') }}" class="text-gray-600 hover:text-[#D4BE83]">Riwayat</a> 
            @endauth

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
                        <a href="/account">Profil</a>
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
        @guest
        <li><a href="{{ route('landing') }}" class="text-gray-600 hover:text-[#D4BE83]">Beranda</a></li>
        <li><a href="{{ route('articles') }}" class="text-gray-600 hover:text-[#D4BE83]">Artikel</a></li>
        <li><a href="#" class="text-gray-600 hover:text-[#D4BE83]">Kalender</a></li>
        <li><a href="#" class="text-gray-600 hover:text-[#D4BE83]">Riwayat</a></li>
        @endguest

        @auth
        <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-[#D4BE83]">Beranda</a></li>
        <li><a href="{{ route('articles') }}" class="text-gray-600 hover:text-[#D4BE83]">Artikel</a></li>
        <li><a href="{{ route('calendar.create') }}" class="text-gray-600 hover:text-[#D4BE83]">Kalender</a></li>
        <li><a href="{{ route('riwayat.menstruasi') }}" class="text-gray-600 hover:text-[#D4BE83]">Riwayat</a></li>  
        @endauth
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
            <a href="/account" class="block text-center text-[#5F7E78] border border-[#5F7E78] px-4 py-2 rounded-xl hover:bg-teal-50 mb-4">Profil</a>

            <!-- Logout Button -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="block w-full text-center bg-[#5F7E78] text-white px-4 py-2 rounded-xl hover:bg-[#4e6863]">Keluar</button>
            </form>
        @endauth
    </div>
</div>

<!-- Konten Artikel -->
<div class="container mx-auto mt-2 py-10 px-4">
    <div class="content-container">
        <div class="article-content">
            <!-- Judul Utama -->
            <h1 class="main-title">Panduan Lengkap Qadha Shalat Fardhu</h1>

            <!-- Pengertian Qadha Shalat -->
            <h2 class="section-title">Pengertian Qadha Shalat</h2>
            <p>Qadha shalat adalah pelaksanaan shalat fardhu di luar waktunya sebagai pengganti shalat yang terlewat, baik karena lupa, tertidur, atau kondisi lain yang dibenarkan. Mengqadha shalat merupakan kewajiban bagi setiap Muslim yang meninggalkan shalat fardhu, baik disengaja maupun tidak.</p>

            <!-- Hukum Mengqadha Shalat -->
            <h2 class="section-title">Hukum Mengqadha Shalat</h2>
            <ul>
                <li><strong>- Karena Uzur (Lupa atau Tertidur):</strong> <br>Disunnahkan untuk segera mengqadha shalat yang terlewat setelah ingat.</li>
                <li><strong>- Tanpa Uzur (Sengaja):</strong> <br> Wajib segera mengqadha shalat yang ditinggalkan.</li>
            </ul>

            <!-- Qadha Shalat karena Haid -->
            <h2 class="section-title">Qadha Shalat yang Terlewat Karena Haid</h2>
            <ul>
                <li>
                    <strong>- Haid Datang Setelah Masuk Waktu Shalat</strong> <br> seorang wanita belum melaksanakan shalat wajib (misalnya, shalat Dzuhur) dan darah haid mulai keluar setelah waktu shalat masuk, maka ia wajib mengqadha shalat tersebut setelah suci dari haid.
                    <p><strong class="text-black">Contoh Kasus:</strong> Haid datang pada pukul 13:00, sementara waktu Dzuhur sudah masuk pukul 12:00. Jika wanita tersebut belum sempat melaksanakan shalat Dzuhur sebelum haid, maka ia wajib mengqadha shalat Dzuhur setelah haid selesai.</p>
                </li>
                <li>
                    <strong>- Suci dari Haid Sebelum Waktu Shalat Berakhir</strong> <br> Jika seorang wanita suci dari haid sebelum waktu shalat berakhir, ia wajib melaksanakan shalat tersebut sebelum waktu habis.
                    <p><strong class="text-black">Contoh Kasus:</strong> Suci dari haid pada pukul 16:30, sementara waktu Ashar berakhir pukul 17:45. Maka, ia wajib melaksanakan shalat Ashar sebelum waktu habis.</p>
                    <p><strong>Catatan:</strong> Jika waktu yang tersisa hanya cukup untuk takbiratul ihram, ia tetap wajib melaksanakan shalat tersebut.</p>
                </li>
            </ul>
            
            <!-- Tata Cara Qadha Shalat -->
            <h2 class="section-title">Tata Cara Qadha Shalat</h2>
            <p>Pelaksanaan qadha shalat sama dengan shalat fardhu pada waktunya, baik dari segi jumlah rakaat maupun gerakan. Perbedaannya terletak pada niat yang disesuaikan untuk qadha.</p>

            <!-- Niat Qadha Shalat -->
            <h3 class="sub-section-title">Niat Qadha Shalat</h3>
            <ul>
                <li>
                    <strong>1. Niat Qadha Shalat Subuh</strong>
                    <p>اُصَلِّي فَرْضَ الصُّبْحِ رَكْعَتَيْنِ مُسْتَقْبِلَ الْقِبْلَةِ قَضَاءً لِلّٰهِ تَعَالٰى</p>
                    <p><em>Usholli fardhos subhi rok'ataini mustaqbilal qiblati qodho'an lillahi ta'ala.</em></p>
                    <p><strong>Artinya:</strong> "Saya niat mengerjakan shalat fardhu Subuh dua rakaat menghadap kiblat qadha karena Allah Ta'ala."</p>
                </li>
                <li>
                    <strong>2. Niat Qadha Shalat Dzuhur</strong>
                    <p>اُصَلِّي فَرْضَ الظُّهْرِ أَرْبَعَ رَكَعَاتٍ مُسْتَقْبِلَ الْقِبْلَةِ قَضَاءً لِلّٰهِ تَعَالٰى</p>
                    <p><em>Usholli fardhoz zuhri arba'a roka'atin mustaqbilal qiblati qodho'an lillahi ta'ala.</em></p>
                    <p><strong>Artinya:</strong> "Saya niat mengerjakan shalat fardhu Dzuhur empat rakaat menghadap kiblat qadha karena Allah Ta'ala."</p>
                </li>
                <li>
                    <strong>3. Niat Qadha Shalat Ashar</strong>
                    <p>اُصَلِّي فَرْضَ الْعَصْرِ أَرْبَعَ رَكَعَاتٍ مُسْتَقْبِلَ الْقِبْلَةِ قَضَاءً لِلّٰهِ تَعَالٰى</p>
                    <p><em>Usholli fardhol 'ashri arba'a roka'atin mustaqbilal qiblati qodho'an lillahi ta'ala.</em></p>
                    <p><strong>Artinya:</strong> "Saya niat mengerjakan shalat fardhu Ashar empat rakaat menghadap kiblat qadha karena Allah Ta'ala."</p>
                </li>
                <li>
                    <strong>4. Niat Qadha Shalat Maghrib</strong>
                    <p>اُصَلِّي فَرْضَ الْمَغْرِبِ ثَلَاثَ رَكَعَاتٍ مُسْتَقْبِلَ الْقِبْلَةِ قَضَاءً لِلّٰهِ تَعَالٰى</p>
                    <p><em>Usholli fardhol maghribi tsalatsa roka'atin mustaqbilal qiblati qodho'an lillahi ta'ala.</em></p>
                    <p><strong>Artinya:</strong> "Saya niat mengerjakan shalat fardhu Maghrib tiga rakaat menghadap kiblat qadha karena Allah Ta'ala."</p>
                </li>
                <li>
                    <strong>5. Niat Qadha Shalat Isya</strong>
                    <p>اُصَلِّي فَرْضَ الْعِشَاءِ أَرْبَعَ رَكَعَاتٍ مُسْتَقْبِلَ الْقِبْلَةِ قَضَاءً لِلّٰهِ تَعَالٰى</p>
                    <p><em>Usholli fardhol isya'i arba'a roka'atin mustaqbilal qiblati qodho'an lillahi ta'ala.</em></p>
                    <p><strong>Artinya:</strong> "Saya niat mengerjakan shalat fardhu Isya empat rakaat menghadap kiblat qadha karena Allah Ta'ala."</p>
                </li>
            </ul>

            <!-- Tata Tertib (Tartib) Qadha -->
            <h2 class="section-title">Tata Tertib (Tartib) Pelaksanaan Qadha Shalat</h2>
            <ul>
                <li>
                    <strong>- Disunnahkan Tartib</strong> <br>Shalat yang terlewat dikerjakan secara berurutan.
                    <p><strong class="text-black">Contoh Kasus:</strong> Jika meninggalkan shalat Subuh dan Dzuhur, maka qadha dilakukan: Qadha Subuh → Qadha Dzuhur → Shalat lainnya.</p>
                </li>
                <li>
                    <strong>- Mendahulukan Qadha atas Shalat Hadir</strong> <br>Jika waktu shalat masih panjang.
                    <p><strong class="text-black">Contoh Kasus:</strong> Jika lupa Subuh dan waktu Dzuhur telah masuk, maka urutannya: Qadha Subuh → Shalat Dzuhur.</p>
                </li>
                <li>
                    <strong>- Wajib Mendahulukan Shalat Hadir Jika Waktunya Hampir Habis</strong> <br>Jika waktu shalat yang hadir hampir habis, wajib mendahulukannya.
                    <p><strong class="text-black">Contoh Kasus:</strong> Jika lupa Subuh, tetapi waktu Dzuhur hampir habis, maka urutannya: Shalat Dzuhur → Qadha Subuh.</p>
                </li>
                <li>
                    <strong>- Shalat Tanpa Uzur Didahulukan</strong> <br>Shalat yang ditinggalkan tanpa uzur didahulukan atas yang dengan uzur.
                    <p><strong class="text-black">Contoh Kasus:</strong> Jika meninggalkan Dzuhur (tanpa uzur) dan Ashar (dengan uzur), maka urutannya: Qadha Dzuhur → Qadha Ashar.</p>
                </li>
            </ul>

            <!-- Sumber Referensi -->
            <h2 class="section-title">Sumber Referensi</h2>
            <ul style="list-style-type: disc; margin-left: 20px;">
                <li>
                    <a href="https://lampung.nu.or.id/syiar/cara-mengqadha-shalat-dengan-baik-dan-benar-9fYUh" target="_blank" style="color: blue; text-decoration: underline;">
                        Cara Mengqadha Shalat dengan Baik dan Benar
                    </a>
                </li>
                <li>
                    <a href="https://www.detik.com/hikmah/khazanah/d-6876402/niat-qadha-sholat-fardhu-yang-terlewat-beserta-tata-cara-dan-waktunya" target="_blank" style="color: blue; text-decoration: underline;">
                        Niat Qadha Sholat Fardhu yang Terlewat Beserta Tata Cara dan Waktunya
                    </a>
                </li>
            </ul>
                                    
            <!-- Tombol Kembali -->
            <a href="@auth {{ route('home') }} @else {{ route('landing') }} @endauth" class="back-button">← Kembali ke Beranda</a>
        </div>
    </div>
</div>

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