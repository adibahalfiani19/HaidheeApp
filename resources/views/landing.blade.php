<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Haidhee - Pencatatan Menstruasi Islami</title>
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    @vite('resources/css/app.css')
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

        /* Styling untuk layar penuh */
        @media (min-width: 1024px) {
            .prayer-wrapper {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1.5rem;
                max-width: 900px;
                margin: 0 auto;
                align-items: stretch; /* Buat tinggi kotak sama */
            }

            .countdown-box,
            .prayer-times-box {
                border-radius: 12px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                padding: 2rem;
                background-color: #ffffff;
                display: flex;
                flex-direction: column;
                justify-content: center; /* Pusatkan konten di dalam kotak */
            }

            .countdown-box {
                background-color: #CDE4DE;
            }

            .prayer-times {
                display: grid;
                grid-template-columns: repeat(3, 1fr); /* Tetap 3 kolom */
                gap: 1rem; /* Jarak antar kolom */
                justify-items: center; /* Pusatkan setiap item di grid */
            }
        }

        /* Untuk layar medium: tetap kotak dengan layout vertikal */
        @media (max-width: 1024px) {
            .prayer-wrapper {
                display: flex;
                flex-direction: column;
                gap: 1rem;
                align-items: center;
            }

            .countdown-box,
            .prayer-times-box {
                width: 100%;
                padding: 1.5rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Tambahkan shadow */
                border-radius: 12px;
                background-color: #ffffff; /* Tetap ada background */
            }

            .countdown-box {
                background-color: #CDE4DE; /* Warna hijau pucat */
                text-align: center; /* Rata tengah konten */
            }

            .prayer-times-box {
                text-align: center; /* Jadwal sholat rata tengah */
            }

            .prayer-times {
                display: grid;
                grid-template-columns: repeat(3, 1fr); /* Tetap 3 kolom */
                gap: 1rem;
            }
        }

        /* Untuk layar kecil: tata ulang konten */
        @media (max-width: 768px) {
            .prayer-wrapper {
                gap: 1rem;
                margin: 0 auto;
            }

            .countdown-box,
            .prayer-times-box {
                padding: 1rem; /* Padding lebih kecil */
                text-align: center;
            }

            .prayer-times {
                grid-template-columns: repeat(3, 1fr); /* 2 kolom untuk layar kecil */
                gap: 0.5rem;
            }
        }

        /* Responsif untuk layar sangat kecil */
        @media (max-width: 480px) {
            .countdown-box,
            .prayer-times-box {
                padding: 0.75rem;
                text-align: center;
            }

            .prayer-times {
                grid-template-columns: repeat(2, 1fr); /* 2 kolom untuk layar kecil */
                gap: 0.25rem;
            }
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
        <nav class="hidden lg:flex items-center space-x-6 ml-auto">
            <ul class="flex space-x-6">
                <li><a href="#" class="text-gray-600 hover:text-[#D4BE83]">Beranda</a></li>
                <li><a href="{{ route('articles') }}" class="text-gray-600 hover:text-[#D4BE83]">Artikel</a></li>
                <li><a href="#" class="text-gray-400">Kalender</a></li>
                <li><a href="#" class="text-gray-400">Riwayat</a></li>
            </ul>
            <div class="flex items-center space-x-3">
                <a href="#" id="loginButton" class="text-[#5F7E78] font-semibold border border-[#5F7E78] px-4 py-2 rounded-xl hover:bg-teal-50">Masuk</a>
                <a href="#" id="registerButton" class="bg-[#5F7E78] text-white font-semibold px-4 py-2 rounded-xl hover:bg-[#4e6863]">Daftar</a>
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
        <li><a href="#" class="text-gray-600 hover:text-[#D4BE83]">Beranda</a></li>
        <li><a href="{{ route('articles') }}" class="text-gray-600 hover:text-[#D4BE83]">Artikel</a></li>
        <li><a href="#" class="text-gray-400">Kalender</a></li>
        <li><a href="#" class="text-gray-400">Riwayat</a></li>
    </ul>
    <!-- Login and Register Buttons Inside Sidebar -->
    <div class="p-4">
        <a href="#" id="loginButtonSidebar" class="block text-center text-[#5F7E78] border border-[#5F7E78] px-4 py-2 rounded-xl hover:bg-teal-50 mb-4">Masuk</a>
        <a href="#" id="registerButtonSidebar" class="block text-center bg-[#5F7E78] text-white px-4 py-2 rounded-xl hover:bg-[#4e6863]">Daftar</a>
    </div>
</div>

<!-- Hero Section -->
<section class="hero-section py-16 bg-gradient-to-b from-[#5F7E78] to-[#B8D6D0] rounded-b-3xl">
    <div class="container mx-auto px-14 flex flex-col lg:flex-row items-center">
        <!-- Text Content -->
        <div class="lg:w-1/2 text-center lg:text-left">
            <h1 class="text-4xl font-bold text-white mb-4">Catat dan Kelola Menstruasimu dengan Lebih Mudah</h1>
            <p class="text-white mb-10">Selamat datang di Haidee, aplikasi pencatatan menstruasi yang dirancang khusus untuk wanita muslimah. Dengan fitur-fitur unggulan yang kami tawarkan, Haidee akan menjadi teman setia dalam menjalani siklus menstruasimu dengan lebih teratur dan sesuai dengan tuntunan Islam.</p>
            <a href="#" id="tryNowButton" class="bg-[#F1EAD7] text-[#5F7E78] px-6 py-3 rounded-xl font-semibold hover:bg-[#D4BE83] transition duration-300 shadow-md">Coba Sekarang</a>
        </div>

        <!-- Image Content -->
        <div class="lg:w-1/2 mt-10 lg:mt-0 flex justify-center lg:justify-end">
            <img src="{{ asset('images/Landing-Haidhee.png') }}" alt="Ilustrasi Pencatatan Menstruasi" class="w-full max-w-xs lg:max-w-md hidden lg:block ">
        </div>
    </div>
</section>

<!-- Lokasi dan Waktu Sholat -->
<section class="py-10 bg-gray-100">
    <div class="container mx-auto px-4">
        <!-- Wrapper Utama -->
        <div class="prayer-wrapper">
            <!-- Bagian Countdown -->
            <div class="countdown-box">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center mb-2">
                    <i class="fas fa-map-marker-alt text-pink-500 mr-2"></i>
                    <span id="region">Sedang Memuat Lokasi...</span>
                </h2>
                <p class="text-gray-600 font-medium">
                    Sholat selanjutnya: <span id="next-prayer" class="font-bold text-gray-800">-</span>
                </p>
                <div id="countdown" class="text-6xl font-bold text-gray-900 mt-4">00:00:00</div>
            </div>
        
            <!-- Bagian Jadwal Sholat -->
            <div class="prayer-times-box">
                <h3 class="text-lg font-semibold text-center text-[#5F7E78] mb-4 border-b-2 border-gray-200 pb-2">
                    Jadwal Sholat Hari Ini!
                </h3>
                <div id="prayer-times" class="prayer-times text-[#5F7E78] font-medium text-lg">
                    <!-- Jadwal akan diisi JS -->
                </div>
            </div>
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
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Pengingat Qada Salat Setelah Masa Menstruasi</h3>
                <p class="text-gray-600">Tidak perlu khawatir tentang ketinggalan salat setelah masa haid berakhir. Haidhee memberikan informasi dan notifikasi tentang salat yang perlu diqada setelah menstruasi selesai, berdasarkan catatan Anda. Jadikan ibadah salat lebih teratur dan bermakna dengan bantuan Haidhee.</p>
            </div>
        </div>
    </div>
</section>

<!-- Tombol Panduan -->
<div class="flex flex-col md:flex-row justify-center items-center gap-8 px-6">
    <a href="{{ route('panduan.haid') }}" class="flex items-center justify-center bg-[#D4BE83] text-white font-bold px-10 py-6 rounded-xl shadow-lg hover:scale-105 transition-transform duration-300 w-full md:w-auto max-w-md">
        <img src="{{ asset('images/darah-icon.png') }}" alt="Panduan Haid/Istihadhah" class="w-16 h-16 mr-6">
        <div class="text-left">
            <span>Panduan Penentuan Darah</span><br>
            <span class="text-xl font-bold text-black">Haid/Istihadhah</span>
        </div>
    </a>
    <a href="{{ route('panduan.qadha') }}" class="flex items-center justify-center bg-[#D4BE83] text-white font-bold px-10 py-6 rounded-xl shadow-lg hover:scale-105 transition-transform duration-300 w-full md:w-auto max-w-md">
        <img src="{{ asset('images/qada-icon.png') }}" alt="Panduan Qadha Salat" class="w-16 h-16 mr-6">
        <div class="text-left">
            <span>Tata Cara</span><br>
            <span class="text-xl font-bold text-black">Qadha Salat</span>
        </div>
    </a>
</div>

<!-- Article Section -->
<section class="mt-12 article-section py-16 bg-[#B8D6D0] rounded-t-3xl rounded-b-3xl">
    <div class="container mx-auto px-6 md:px-14">
        <h2 class="text-xl font-bold text-center text-gray-800 mb-12">Pelajari lebih banyak lagi tentang menstruasi dalam Islam!</h2>
        
        <!-- Looping data artikel terbaru -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-18">
            @foreach($latestArticles as $article)
                <a href="{{ route('article.detail', ['id' => $article->id]) }}" class="bg-white px-5 py-5 rounded-2xl shadow-md h-[450px] block transition-transform transform hover:scale-105">
                    <!-- Gambar artikel -->
                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="rounded-lg mb-6 w-full h-[250px] object-cover">
                    
                    <!-- Judul artikel -->
                    <h3 class="text-md font-semibold text-gray-800 mb-4">{{ $article->title }}</h3>
                    
                    <!-- Tampilkan deskripsi singkat atau beberapa baris dari konten jika diinginkan -->
                    <p class="text-gray-600">{{ Str::limit($article->content, 100) }}</p>
                </a>
            @endforeach
        </div>
        
        <div class="flex justify-center mt-12">
            <a href="{{ route('articles') }}" class="bg-white text-[#5F7E78] px-6 py-3 rounded-xl font-semibold hover:bg-[#4e6863] transition duration-300 shadow-md">Lihat Lebih Banyak</a>
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
    document.addEventListener("DOMContentLoaded", function () {
        fetch("{{ route('prayer.times') }}")
            .then(response => response.json())
            .then(data => {
                // Tampilkan Lokasi
                document.getElementById('region').textContent = ` ${data.region}`;
    
                // Tampilkan Jadwal Sholat
                const prayerTimes = data.prayerTimes;
                const prayerContainer = document.getElementById('prayer-times');
    
                const selectedPrayers = ['Fajr', 'Sunrise', 'Dhuhr', 'Asr', 'Maghrib', 'Isha'];
                const filteredPrayers = selectedPrayers.map(prayer => ({
                    name: prayer,
                    time: prayerTimes[prayer],
                }));
    
                prayerContainer.innerHTML = ''; // Kosongkan sebelum render
    
                // Render waktu sholat yang dipilih
                filteredPrayers.forEach(prayer => {
                    const div = document.createElement('div');
                    div.className = "text-center";
                    div.innerHTML = `<span class="block font-semibold">${prayer.name}</span>
                                     <span class="block text-gray-800">${prayer.time}</span>`;
                    prayerContainer.appendChild(div);
                });
    
                // Sholat Selanjutnya
                const now = new Date();
                const nextPrayer = filteredPrayers.find(prayer => {
                    const [hour, minute] = prayer.time.split(':').map(Number);
                    const prayerTime = new Date();
                    prayerTime.setHours(hour, minute, 0, 0);
                    console.log(`Checking ${prayer.name}: PrayerTime=${prayerTime}, Now=${now}, IsNext=${prayerTime > now}`);
                    return prayerTime > now;
                });
    
                if (nextPrayer) {
                    console.log("Next Prayer Found:", nextPrayer);
                    document.getElementById('next-prayer').textContent = nextPrayer.name;
    
                    // Hitung waktu tersisa untuk countdown
                    const [hour, minute] = nextPrayer.time.split(':').map(Number);
                    const prayerDate = new Date();
                    prayerDate.setHours(hour, minute, 0, 0);
                    const remainingTime = Math.max(0, (prayerDate - now) / 1000); // Dalam detik
    
                    startCountdown(remainingTime);
                } else {
                    // Fallback jika semua sholat hari ini sudah selesai
                    const firstPrayer = filteredPrayers[0];
                    const [hour, minute] = firstPrayer.time.split(':').map(Number);
                    const firstPrayerTime = new Date();
                    firstPrayerTime.setDate(now.getDate() + 1); // Tambahkan 1 hari
                    firstPrayerTime.setHours(hour, minute, 0, 0);
    
                    document.getElementById('next-prayer').textContent = `${firstPrayer.name}`;
                    const remainingTime = Math.max(0, (firstPrayerTime - now) / 1000);
                    startCountdown(remainingTime);
                }
            });
    
        // Countdown Timer
        function startCountdown(remainingTime) {
            const countdownElement = document.getElementById('countdown');
    
            const interval = setInterval(() => {
                if (remainingTime <= 0) {
                    clearInterval(interval);
                    countdownElement.textContent = '00:00:00';
                    return;
                }
    
                const hours = Math.floor(remainingTime / 3600);
                const minutes = Math.floor((remainingTime % 3600) / 60);
                const seconds = Math.floor(remainingTime % 60);
    
                countdownElement.textContent =
                    `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    
                remainingTime--;
            }, 1000);
        }
    });
    
    </script>

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

    document.addEventListener('DOMContentLoaded', function () {
        // Cek apakah ada session 'registrationSuccess'
        @if(session('registrationSuccess'))
            // Tampilkan modal login secara otomatis
            document.getElementById('loginModal').classList.remove('hidden');
        @endif
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Cek apakah ada session 'loginError'
        @if(session('loginError'))
            // Tampilkan alert dengan pesan error
            alert("{{ session('loginError') }}");

            // Tampilkan modal login otomatis
            document.getElementById('loginModal').classList.remove('hidden');
        @endif
    });
</script>

</body>
</html>
