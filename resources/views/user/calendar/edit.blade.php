<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kalender (Edit) - Haidhee</title>
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">

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
            z-index: 1050; /* Ensures sidebar appears above header */
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

        .calendar-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding-top: 30px;
            margin-bottom: 50px;
        }
        .calendar-card {
            display: flex;
            padding: 20px;
            background: linear-gradient(135deg, #A0B7B6, #D6E0E0);
            border-radius: 20px;
            width: 90%;
            max-width: 800px;
            height: 400px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            align-items: center;
            justify-content: space-between; 
        }
        .calendar-section {
            width: 50%;
            padding: 20px;
            text-align: center;
            display: flex;
            align-items: stretch;
            justify-content: center;
            height: 100%;
        }
        #calendar {
            background-color: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            height: 300px; /* Pastikan tinggi konsisten */
            position: relative;
            z-index: 1; /* Pastikan ini lebih rendah dari modal */
        }

        .form-section {
            width: 50%;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }
        .placeholder {
            color: #999;
            text-align: center;
        }
        .menstruation-form {
            display: none;
            width: 100%;
            background-color: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            height: 300px;
        }
        .btn-save {
            background-color: #5F7E78;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 10px;
        }

        .pika-single {
            border: none !important;
            box-shadow: none !important;
            width: 100% !important; /* Pastikan Pikaday mengikuti lebar penuh */
            height: 100% !important; /* Pastikan Pikaday mengikuti tinggi penuh */
            display: flex;
            align-items: center;
            flex-direction: column;
            justify-content: center;
            padding-bottom: 25px
        }
        .pika-lendar {
            border: none !important;
            width: 100% !important;
            height: 100% !important;
        }
        .pika-title, .pika-prev, .pika-next, .pika-label {
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
        }
        .pika-table {
            border-collapse: collapse !important;
            width: 100% !important;
            height: 100% !important;
            table-layout: fixed;
            /* height: 100% !important; */
        }
        .pika-button {
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
            background-color: transparent;
        }
        .pika-button:hover {
            background-color: #D4BE83; 
        }  

        #prayer {
            display: none;
        }
        label[for="prayer"] {
            display: none;
        }

        @media (max-width: 668px) {
            .calendar-card {
                flex-direction: column; /* Ubah dari horizontal ke vertikal */
                height: auto; /* Biarkan tinggi menyesuaikan secara otomatis */
            }
            .calendar-section, .form-section {
                width: 100%; /* Isi lebar penuh */
                padding: 10px; /* Berikan sedikit padding */
                margin-bottom: 10px; /* Tambahkan jarak antar elemen */
            }
            #calendar, .menstruation-form {
                height: auto; /* Biarkan tinggi menyesuaikan */
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
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-[#D4BE83]">Beranda</a>
            <a href="{{ route('articles') }}" class="text-gray-600 hover:text-[#D4BE83]">Artikel</a>
            <a href="{{ route('calendar.create') }}" class="text-gray-600 hover:text-[#D4BE83]">Kalender</a>
            <a href="{{ route('riwayat.menstruasi') }}" class="text-gray-600 hover:text-[#D4BE83]">Riwayat</a> 
            
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
                        <a href="{{ route('profile.index')}}">Profil</a>
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
        <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-[#D4BE83]">Beranda</a></li>
        <li><a href="{{ route('articles') }}" class="text-gray-600 hover:text-[#D4BE83]">Artikel</a></li>
        <li><a href="{{ route('calendar.create') }}" class="text-gray-600 hover:text-[#D4BE83]">Kalender</a></li>
        <li><a href="{{ route('riwayat.menstruasi') }}" class="text-gray-600 hover:text-[#D4BE83]">Riwayat</a></li>  
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
            <a href="{{ route('profile.index')}}" class="block text-center text-[#5F7E78] border border-[#5F7E78] px-4 py-2 rounded-xl hover:bg-teal-50 mb-4">Profil</a>
            <!-- Logout Button -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="block w-full text-center bg-[#5F7E78] text-white px-4 py-2 rounded-xl hover:bg-[#4e6863]">Keluar</button>
            </form>
        @endauth
    </div>
</div>

{{-- Calender section --}}
<div style="text-align: center; font-size: 1.5em; font-weight: bold; color: #5F7E78; margin-top: 60px;">
    Edit Data Menstruasi Kamu!
</div>

<div class="calendar-container">
    <div class="calendar-card">
        <!-- Bagian Kalender -->
        <div class="calendar-section">
            <div id="calendar" style="margin-top: 10px;"></div>
        </div>

        <!-- Bagian Form -->
        <div class="form-section">
            <!-- Form aktif secara default -->
            <div id="date-selected-form" class="menstruation-form" style="display: block;">
                <!-- Tombol Switching -->
                <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                    <button id="startButton" style="flex: 1; padding: 10px; background-color: #5F7E78; border: none; border-radius: 5px; cursor: pointer;">
                        Mulai
                    </button>
                    <button id="endButton" style="flex: 1; padding: 10px; background-color: #e0f2f1; border: none; border-radius: 5px; cursor: pointer;">
                        Selesai
                    </button>
                </div>
                
                <!-- Form Mulai -->
                <div id="form-start" style="{{ !$menstruation->end_date ? 'display: block;' : 'display: none;' }}">
                    <input type="hidden" id="menstruation-id" value="{{ $menstruation->id ?? '' }}">
                    <label for="start-date" style="font-size: 0.9rem; color: #666;">Tanggal</label>
                    <input type="text" id="start-date" name="start-date" readonly style="margin-bottom: 10px; font-weight: bold;" value="{{ \Carbon\Carbon::parse($menstruation->start_date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}">
                    <input type="hidden" id="start-date-server" value="{{ $menstruation->start_date }}">
                
                    <div style="margin-bottom: 10px;">
                        <label for="start-time" style="font-size: 0.9rem; color: #666;">Pukul</label>
                        <input type="time" id="start-time" name="start-time" style="padding: 5px; border-radius: 5px; border: 1px solid #ccc; width: 100%;" value="{{ $menstruation->start_time }}">
                    </div>
                
                    <div style="margin-bottom: 10px;">
                        <input type="checkbox" id="prayer-start" name="prayer-start" value="sudah sholat" {{ $menstruation->prayer_start == null ? 'checked' : '' }}>
                        <label for="prayer-start" id="prayer-start-label" style="font-size: 0.9rem; color: #666;">{{ $menstruation->prayer_start == null ? 'Saya sudah sholat' : 'Saya sudah sholat ' . ucfirst($menstruation->prayer_start) }}</label>
                    </div>
                
                    <button id="save-start" class="btn-save" type="button" style="background-color: #5F7E78;">Simpan</button>
                </div>                

                <!-- Form Selesai -->
                <div id="form-end" style="{{ $menstruation->end_date ? 'display: block;' : 'display: none;' }}">
                    <input type="hidden" id="menstruation-id" value="{{ $menstruation->id ?? '' }}">
                    <label for="end-date" style="font-size: 0.9rem; color: #666;">Tanggal</label>
                    <input type="text" id="end-date" name="end-date" readonly style="margin-bottom: 10px; font-weight: bold;" value="{{ $menstruation->end_date ? \Carbon\Carbon::parse($menstruation->end_date)->locale('id')->isoFormat('dddd, D MMMM YYYY') : '' }}">
                    <input type="hidden" id="end-date-server" value="{{ $menstruation->end_date ?? '' }}">

                    <div style="margin-bottom: 10px;">
                        <label for="end-time" style="font-size: 0.9rem; color: #666;">Pukul</label>
                        <input type="time" id="end-time" name="end-time" style="padding: 5px; border-radius: 5px; border: 1px solid #ccc; width: 100%;" value="{{ $menstruation->end_time ?? '' }}">
                    </div>

                    <div style="margin-bottom: 10px;">
                        <input type="checkbox" id="prayer-end" name="prayer-end" value="sudah sholat" {{ is_null($menstruation->prayer_end) && !is_null($menstruation->end_date) ? 'checked' : '' }}>
                        <label for="prayer-end" style="font-size: 0.9rem; color: #666;">-</label>
                    </div>

                    <button id="save-end" class="btn-save" type="button" style="background-color: #5F7E78;">Simpan</button>
                </div>

            </div>
        </div>
    </div>
</div>

@include('components.popup')

<script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>

<script>
function validateForm(type) {
    if (type === 'start') {
        const startDate = document.getElementById('start-date-server').value;
        const startTime = document.getElementById('start-time').value;

        // Validasi apakah tanggal dan jam mulai sudah diisi
        if (!startDate || !startTime) {
            alert("Lengkapi data pada form Mulai terlebih dahulu!");
            return false; // Gagal validasi
        }
    }

    if (type === 'end') {
        const endDate = document.getElementById('end-date-server').value;
        const endTime = document.getElementById('end-time').value;

        // Validasi apakah tanggal dan jam selesai sudah diisi
        if (!endDate || !endTime) {
            alert("Lengkapi data pada form Selesai terlebih dahulu!");
            return false; // Gagal validasi
        }
    }

    return true; // Berhasil validasi
}

function saveData(type) {
    if (!validateForm(type)) {
        return; // Hentikan proses jika validasi gagal
    }

    const menstruationId = document.getElementById('menstruation-id').value;
    let url, method;

    // Tentukan URL dan metode (POST untuk create, PUT untuk update)
    if (menstruationId) {
        url = `/calendar/${menstruationId}`; // URL untuk update data berdasarkan ID
        method = 'PUT';
    } else {
        url = '/calendar'; // URL untuk create data baru
        method = 'POST';
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Buat objek data yang akan dikirim ke server
    let data = {
        start_date: document.getElementById('start-date-server').value,
        start_time: document.getElementById('start-time').value,
        prayer_start: document.getElementById('prayer-start').checked ? null : document.querySelector('label[for="prayer-start"]').textContent.trim().replace('Saya sudah sholat ', ''),
        end_date: document.getElementById('end-date-server').value,
        end_time: document.getElementById('end-time').value,
        prayer_end: document.getElementById('prayer-end').checked ? null : document.querySelector('label[for="prayer-end"]').textContent.trim().replace('Saya sudah sholat ', ''),
    };

    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
    .then((response) => {
        if (!response.ok) {
            throw new Error(`HTTP Error: ${response.status}`);
        }
        return response.json();
    })
    .then((data) => {
        // Tampilkan pesan popup
        const message = 'Data menstruasimu berhasil diperbarui!';
        showPopup(message);
    })
    .catch((error) => {
            console.error('Detail Error:', error);
            alert('Terjadi kesalahan saat menyimpan data.');
        });
}

// Fungsi untuk mengisi nilai form berdasarkan data
function setFormValues(data) {
    if (data.start_date) {
        document.getElementById('start-date').value = data.start_date;
        document.getElementById('start-date-server').value = data.start_date;
    }
    if (data.start_time) {
        document.getElementById('start-time').value = data.start_time;
    }
    if (data.prayer_start) {
        const prayerStartLabel = document.querySelector('label[for="prayer-start"]');
        prayerStartLabel.textContent = 'Saya sudah sholat ' + data.prayer_start;
        document.getElementById('prayer-start').checked = false; // Tidak dicentang jika ada data
    } else {
        document.getElementById('prayer-start').checked = true; // Dicentang jika tidak ada data
    }

    if (data.end_date) {
        document.getElementById('end-date').value = data.end_date;
        document.getElementById('end-date-server').value = data.end_date;
    }
    if (data.end_time) {
        document.getElementById('end-time').value = data.end_time;
    }
    if (data.prayer_end) {
        const prayerEndLabel = document.querySelector('label[for="prayer-end"]');
        prayerEndLabel.textContent = 'Saya sudah sholat ' + data.prayer_end;
        document.getElementById('prayer-end').checked = false;
    } else {
        document.getElementById('prayer-end').checked = true;
    }
}

// Panggil fungsi untuk mengisi nilai form saat halaman dimuat
document.addEventListener('DOMContentLoaded', function () {
    setFormValues(menstruationData); // Sesuaikan dengan data yang sudah didefinisikan
});

document.addEventListener('DOMContentLoaded', function () {
    const formStart = document.getElementById('form-start');
    const formEnd = document.getElementById('form-end');
    const startButton = document.getElementById('startButton');
    const endButton = document.getElementById('endButton');
    const dateSelectedForm = document.getElementById('date-selected-form');

    // Pastikan form terlihat saat halaman pertama kali dimuat
    dateSelectedForm.style.display = 'block';

    if (formEnd.style.display === 'block') {
        // Jika form "Selesai" yang aktif
        startButton.style.backgroundColor = '#e0f2f1';
        endButton.style.backgroundColor = '#5F7E78';
    } else {
        // Jika form "Mulai" yang aktif
        startButton.style.backgroundColor = '#5F7E78';
        endButton.style.backgroundColor = '#e0f2f1';
    }

    // Saat tombol "Mulai" diklik
    startButton.addEventListener('click', function () {
        formStart.style.display = 'block';
        formEnd.style.display = 'none';
        startButton.style.backgroundColor = '#5F7E78';
        endButton.style.backgroundColor = '#e0f2f1';
    });

    // Saat tombol "Selesai" diklik
    endButton.addEventListener('click', function () {
        formStart.style.display = 'none';
        formEnd.style.display = 'block';
        startButton.style.backgroundColor = '#e0f2f1';
        endButton.style.backgroundColor = '#5F7E78';
    });

    setFormValues(menstruationData); 
});

// Fungsi untuk menampilkan pop-up dengan pesan tertentu
function showPopup(message) {
    const popupModal = document.getElementById('popupModal');
    const popupMessage = document.getElementById('popupMessage');
    popupMessage.textContent = message; // Set pesan dinamis
    popupModal.classList.remove('hidden'); // Tampilkan modal
}

// Tambahkan event listener saat tombol simpan diklik
document.getElementById('save-start').addEventListener('click', function () {
    event.preventDefault(); 
    saveData('start'); // Fungsi save data
});

document.getElementById('save-end').addEventListener('click', function () {
    event.preventDefault(); 
    saveData('end'); // Fungsi save data
});


const calendar = new Pikaday({
    field: document.getElementById('calendar'),
    bound: false, // Agar kalender selalu tampil
    container: document.getElementById('calendar'),
    onSelect: function (date) {
        const dateSelectedForm = document.getElementById('date-selected-form');

        // Format untuk tampilan
        const displayDate = date.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        });

        const dateInLocal = new Date(date.getTime() - date.getTimezoneOffset() * 60000);
        const serverDate = dateInLocal.toISOString().split('T')[0];

        // Tampilkan form dan sembunyikan placeholder
        dateSelectedForm.style.display = 'block';

        // Cek form aktif dan perbarui tanggal yang ditampilkan
        const formStart = document.getElementById('form-start');
        const formEnd = document.getElementById('form-end');

        if (formStart.style.display === 'block') {
            // Perbarui tanggal di form start
            document.getElementById('start-date').value = displayDate;
            document.getElementById('start-date-server').value = serverDate;
        } else if (formEnd.style.display === 'block') {
            // Perbarui tanggal di form end
            document.getElementById('end-date').value = displayDate;
            document.getElementById('end-date-server').value = serverDate;
        }
    },
});

document.addEventListener('DOMContentLoaded', function () {
    // Ambil waktu salat saat halaman dimuat
    getPrayerTimes();

    // Update checkbox berdasarkan waktu di form mulai dan selesai
    const startTimeInput = document.getElementById('start-time');
    const endTimeInput = document.getElementById('end-time');
    const prayerLabelStart = document.querySelector('label[for="prayer-start"]');
    const prayerCheckboxStart = document.getElementById('prayer-start');
    const prayerLabelEnd = document.querySelector('label[for="prayer-end"]');
    const prayerCheckboxEnd = document.getElementById('prayer-end');

    // Update label checkbox saat halaman di-load pertama kali untuk form mulai
    updatePrayerLabel(startTimeInput.value, prayerLabelStart);

    // Update label checkbox saat halaman di-load pertama kali untuk form selesai (hanya jika end-time sudah ada)
    if (endTimeInput.value) {
        updatePrayerLabel(endTimeInput.value, prayerLabelEnd);
    }

    // Event listener untuk input waktu form mulai
    startTimeInput.addEventListener('change', function () {
        updatePrayerLabel(this.value, prayerLabelStart);
    });

    // Event listener untuk input waktu form selesai
    endTimeInput.addEventListener('change', function () {
        updatePrayerLabel(this.value, prayerLabelEnd);
    });

    // Fungsi untuk memperbarui label checkbox sholat
    function updatePrayerLabel(time, prayerLabel) {
        const prayerTimes = JSON.parse(localStorage.getItem('prayerTimes'));

        if (!prayerTimes) {
            console.warn('Waktu sholat belum tersedia. Pastikan Anda terhubung ke internet.');
            return;
        }

        const userHour = parseInt(time.split(':')[0]);
        const userMinute = parseInt(time.split(':')[1]);

        function isBetween(time, start, end) {
            const [startHour, startMinute] = start.split(':').map(Number);
            const [endHour, endMinute] = end.split(':').map(Number);

            const userTime = userHour * 60 + userMinute;
            const startTime = startHour * 60 + startMinute;
            const endTime = endHour * 60 + endMinute;

            return userTime >= startTime && userTime <= endTime;
        }

        // Update label checkbox berdasarkan waktu
        let prayerText = '';
        if (isBetween(time, prayerTimes.Fajr, prayerTimes.Dhuhr)) {
            prayerText = 'Saya sudah sholat Subuh';
        } else if (isBetween(time, prayerTimes.Dhuhr, prayerTimes.Asr)) {
            prayerText = 'Saya sudah sholat Dzuhur';
        } else if (isBetween(time, prayerTimes.Asr, prayerTimes.Maghrib)) {
            prayerText = 'Saya sudah sholat Ashar';
        } else if (isBetween(time, prayerTimes.Maghrib, prayerTimes.Isha)) {
            prayerText = 'Saya sudah sholat Maghrib';
        } else if (isBetween(time, prayerTimes.Isha, '23:59')) {
            prayerText = 'Saya sudah sholat Isya';
        } else if (isBetween(time, '00:00', prayerTimes.Fajr)) {
            prayerText = 'Saya sudah sholat Isya';
        } else {
            prayerText = 'Waktu sholat tidak ditemukan';
        }

        // Perbarui teks label checkbox sholat
        prayerLabel.textContent = prayerText;
    }

    // Fungsi untuk mengambil waktu salat dari server
    async function getPrayerTimes() {
        try {
            // Ambil lokasi berdasarkan IP
            const ipResponse = await fetch('http://ip-api.com/json/');
            const location = await ipResponse.json();
            const latitude = location.lat;
            const longitude = location.lon;

            // Ambil waktu salat dari API
            const prayerResponse = await fetch(`/prayer-times?latitude=${latitude}&longitude=${longitude}`);
            const prayerData = await prayerResponse.json();

            if (prayerData && prayerData.data && prayerData.data.timings) {
                localStorage.setItem('prayerTimes', JSON.stringify(prayerData.data.timings));
                console.log('Waktu sholat berhasil diambil:', prayerData.data.timings);
            } else {
                console.error('Gagal mengambil waktu sholat');
            }
        } catch (error) {
            console.error('Error saat mengambil lokasi atau waktu sholat:', error);
        }
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

    document.addEventListener('DOMContentLoaded', function() {
        @if(session('registrationSuccess'))
            document.getElementById('loginModal').classList.remove('hidden');
        @endif
    });
</script>
</body>
</html>