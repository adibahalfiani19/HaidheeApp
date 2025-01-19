<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Menstruasi - Haidhee</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        /* Tabel standar hanya muncul di layar lebar */
        @media (min-width: 1030px) {
            table {
                display: table;
            }

            .responsive-table {
                display: none;
            }
        }

        @media (max-width: 1029px) {
            /* Sembunyikan tabel untuk layar kecil */
            table {
                display: none;
            }

            /* Tampilkan versi vertikal untuk tabel */
            .responsive-table {
                display: grid;
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .responsive-table .row {
                background-color: #ffffff;
                padding: 1rem;
                border-radius: 0.5rem;
                box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
            }

            .responsive-table .row > div {
                display: flex;
                flex-direction: row; /* Label dan value sejajar horizontal */
                justify-content: flex-start;
                align-items: center;
                margin-bottom: 0.5rem;
            }

            .responsive-table .row > div:last-child {
                margin-bottom: 0;
            }

            .responsive-table .label {
                font-weight: bold;
                color: #5F7E78;
                flex-basis: 30%; /* Lebar untuk label */
                text-align: left;
                margin-right: 1rem; /* Beri jarak antara label dan value */
            }

            .responsive-table .value {
                color: #333333;
                flex-basis: 65%;
                text-align: left;
            }

            .responsive-table .actions {
                display: flex;
                gap: 0.5rem;
                justify-content: flex-start;
            }

            .responsive-table .actions button,
            .responsive-table .actions a {
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .responsive-table .label {
                flex-basis: 40%; /* Lebar untuk label di layar kecil */
            }

            .responsive-table .value {
                flex-basis: 55%; /* Lebar untuk value di layar kecil */
            }
        }


        #detail-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            display: none;
        }

        #detail-modal .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            max-width: 500px;
            width: 90%;
            margin: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            position: relative; /* Agar tombol close tetap di tempat yang tepat */
        }

        #detail-modal .modal-content h2 {
            color: #5F7E78;
            font-weight: 700;
        }

        #detail-modal .modal-content p {
            margin-bottom: 10px;
            font-size: 1rem;
            color: #333;
        }

        #detail-modal .modal-content hr {
            border-top: 1px solid #e0e0e0;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        #detail-modal .modal-content h3 {
            font-size: 1rem;
            color: #444;
            margin-bottom: 10px;
        }

        #detail-modal .modal-content input[type="checkbox"] {
            margin-right: 10px;
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
            <a href="{{ route('riwayat.menstruasi') }}" class="{{ request()->routeIs('riwayat.menstruasi') ? 'text-[#D4BE83]' : 'text-gray-600 hover:text-[#D4BE83]' }}">Riwayat</a> 
            
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
        <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-[#D4BE83]">Beranda</a></li>
        <li><a href="{{ route('articles') }}" class="text-gray-600 hover:text-[#D4BE83]">Artikel</a></li>
        <li><a href="{{ route('calendar.create') }}" class="text-gray-600 hover:text-[#D4BE83]">Kalender</a></li>
        <li><a href="{{ route('riwayat.menstruasi') }}" class="{{ request()->routeIs('riwayat.menstruasi') ? 'text-[#D4BE83]' : 'text-gray-600 hover:text-[#D4BE83]' }}">Riwayat</a></li>  
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

@if (session('success'))
    <script>
        alert('Data berhasil terhapus');
    </script>
@endif

{{-- Isi Konten --}}
<!-- Statistik Rata-rata -->
<div class="container mx-auto mt-14 px-6 md:px-14 mb-14">
    <h1 class="text-2xl font-bold mb-4">Riwayat Menstruasi</h1>
    <div class="max-w-4xl ml-0 grid grid-cols-3 gap-4 mb-8">
        <!-- Durasi Haid -->
        <div class="bg-[#B8D6D0] text-center py-4 rounded-lg shadow-md">
            <h3 class="text-lg font-bold text-gray-700 mb-1">Durasi Haid</h3>
            <p class="text-sm text-gray-500 mb-2">(Rata-rata)</p>
            <p class="text-3xl font-bold text-[#5F7E78]">{{ $averageHaid ?? '-' }}</p>
            <p class="text-sm text-gray-500">Hari</p>
        </div>

        <!-- Masa Suci -->
        <div class="bg-[#FBE4D5] text-center py-4 rounded-lg shadow-md">
            <h3 class="text-lg font-bold text-gray-700 mb-1">Masa Suci</h3>
            <p class="text-sm text-gray-500 mb-2">(Rata-rata)</p>
            <p class="text-3xl font-bold text-[#D4BE83]">{{ $averageClean ?? '-' }}</p>
            <p class="text-sm text-gray-500">Hari</p>
        </div>

        <!-- Siklus -->
        <div class="bg-[#EADCDC] text-center py-4 rounded-lg shadow-md">
            <h3 class="text-lg font-bold text-gray-700 mb-1">Siklus</h3>
            <p class="text-sm text-gray-500 mb-2">(Rata-rata)</p>
            <p class="text-3xl font-bold text-[#D76A6A]">{{ $averageCycle ?? '-' }}</p>
            <p class="text-sm text-gray-500">Hari</p>
        </div>
    </div>

    <!-- Tabel Riwayat -->
    <div class="overflow-x-auto rounded-xl shadow-lg">
        <!-- Tabel Standar untuk Desktop -->
        <table class="min-w-full bg-white text-center hidden lg:table">
            <thead class="bg-[#5F7E78] text-white">
                <tr>
                    <th class="py-3">No.</th>
                    <th class="py-3">Tanggal Mulai</th>
                    <th class="py-3">Waktu Mulai</th>
                    <th class="py-3">Tanggal Selesai</th>
                    <th class="py-3">Waktu Selesai</th>
                    <th class="py-3">
                        Status
                        <br>
                        <span class="text-sm">(Haid/Istihadah)</span>
                    </th>
                    <th class="py-3">
                        Durasi
                        <br>
                        <span class="text-sm">(Haid-H; Istihadhah-I)</span>
                    </th>                    
                    <th class="py-3">
                        Qada Salat
                        <br>
                        <span class="text-sm">(Ada/Tidak)</span>
                    </th>
                    <th class="py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $index => $data)
                <tr class="even:bg-gray-100">
                    <!-- Perhatikan nomor urut -->
                    <td class="py-3 px-4">{{ ($riwayat->currentPage() - 1) * $riwayat->perPage() + $index + 1 }}</td>
                    <td class="py-3 px-4">{{ $data->start_date }}</td>
                    <td class="py-3 px-4">{{ $data->start_time }}</td>
                    <td class="py-3 px-4">{{ $data->end_date ?? '-' }}</td>
                    <td class="py-3 px-4">{{ $data->end_time ?? '-' }}</td>
                    <td class="py-3 px-4">{{ $data->status ?? '-' }}</td>
                    <td class="py-3 px-4">
                        H: {{ $data->durasi_haid > 0 ? $data->durasi_haid . ' hari' : '-' }}<br>
                        I: {{ $data->durasi_istihadhah > 0 ? $data->durasi_istihadhah . ' hari' : '-' }}
                    </td>                               
                    <td class="py-3 px-4">
                        @php
                            // Cek apakah prayer_start atau prayer_end ada nilainya (tidak null atau tidak kosong)
                            $qadaSalat = (!empty($data->prayer_start) || !empty($data->prayer_end)) ? 'Ada' : 'Tidak';
                            $qadaSalatId = "qada-salat-{$data->id}";
                        @endphp
                        <span id="{{ $qadaSalatId }}" class="text-center px-4 py-2 rounded-lg text-white {{ $qadaSalat == 'Ada' ? 'bg-red-500' : 'bg-yellow-500' }}" style="min-width: 70px; display: inline-block;">
                            {{ $qadaSalat }}
                        </span>
                    </td>
                    <td class="py-3 px-4 flex justify-center space-x-2">
                        <button class="text-blue-500 hover:text-blue-700" onclick="showDetailModal({{ $data->id }})" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <a href="{{ route('calendar.edit', ['id' => $data->id]) }}" class="text-yellow-500 hover:text-yellow-700" title="Edit Data">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('calendar.destroy', ['id' => $data->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" title="Hapus Data">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-4 text-gray-500">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Tabel Versi Responsif -->
        <div class="responsive-table block lg:hidden">
            @forelse($riwayat as $index => $data)
            <div class="row">
                <div>
                    <span class="label">No</span>
                    <span class="value">{{ ($riwayat->currentPage() - 1) * $riwayat->perPage() + $index + 1 }}</span>
                </div>
                <div>
                    <span class="label">Tanggal Mulai</span>
                    <span class="value">{{ $data->start_date }}</span>
                </div>
                <div>
                    <span class="label">Waktu Mulai</span>
                    <span class="value">{{ $data->start_time }}</span>
                </div>
                <div>
                    <span class="label">Tanggal Selesai</span>
                    <span class="value">{{ $data->end_date ?? '-' }}</span>
                </div>
                <div>
                    <span class="label">Waktu Selesai</span>
                    <span class="value">{{ $data->end_time ?? '-' }}</span>
                </div>
                <div>
                    <span class="label">Status</span>
                    <span class="value font-bold">{{ $data->status ?? '-' }}</span>
                </div>
                <div>
                    <span class="label">Durasi</span>
                    <span class="value">
                        Haid: {{ $data->durasi_haid > 0 ? $data->durasi_haid . ' hari' : '-' }} ;
                        Istihadah: {{ $data->durasi_istihadhah > 0 ? $data->durasi_istihadhah . ' hari' : '-' }}
                    </span>
                </div>
                <div>
                    <span class="label">Qada Salat</span>
                    <span class="value font-bold">{{ (!empty($data->prayer_start) || !empty($data->prayer_end)) ? 'Ada' : 'Tidak' }}</span>
                </div>
                <div>
                    <span class="label">Aksi</span>
                    <div class="actions">
                        <button class="text-blue-500 hover:text-blue-700" onclick="showDetailModal({{ $data->id }})" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <a href="{{ route('calendar.edit', ['id' => $data->id]) }}" class="text-yellow-500 hover:text-yellow-700" title="Edit Data">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('calendar.destroy', ['id' => $data->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" title="Hapus Data">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-gray-500">Tidak ada data</p>
            @endforelse
        </div>
    </div>  

    <div id="detail-modal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex justify-center items-center hidden">
        <div class="modal-content bg-white p-6 rounded-md shadow-md relative" style="width: 400px;">
            <div class="flex justify-between items-center mb-4">
                <!-- Bagian Judul dengan Ikon -->
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-[#5F7E78] mr-2"></i> <!-- Tambahkan ikon dengan warna hijau -->
                    <h2 class="text-lg font-semibold text-[#5F7E78]">Detail</h2>
                </div>
                <!-- Tombol Close -->
                <button onclick="closeModal()" class="text-gray-600 hover:text-gray-800 absolute top-4 right-4">
                    <i class="fas fa-times"></i>
                </button>

            </div>
            <div>
                <p><span class="font-semibold">Durasi Haid</span>: <span id="modal-durasi-haid"></span></p>
                <p><span class="font-semibold">Durasi Istihadhah</span>: <span id="modal-durasi-istihadhah"></span></p>
                <hr class="my-3">
                <h3 class="font-semibold text-gray-800 mb-2">Salat yang Perlu Diqada:</h3>
                <div id="qada-salat-list" class="space-y-2">
                    <!-- Daftar salat yang perlu diqada akan dimasukkan di sini -->
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $riwayat->links() }}
    </div>
</div>


<script>
function showDetailModal(menstruationId) {
    // Mendapatkan data menggunakan fetch
    fetch(`/menstruation/detail/${menstruationId}`)
        .then(response => response.json())
        .then(data => {
            console.log('Data qada_salat:', data.qada_salat); // Debug log untuk memastikan data diterima

            // Isi data ke modal
            document.getElementById('modal-durasi-haid').innerText = data.durasi_haid + ' hari';
            document.getElementById('modal-durasi-istihadhah').innerText = data.durasi_istihadhah ? data.durasi_istihadhah + ' hari' : '-';

            const qadaList = document.getElementById('qada-salat-list');
            qadaList.innerHTML = ''; // Hapus konten sebelumnya

            if (data.qada_salat.length > 0) {
                data.qada_salat.forEach(salat => {
                    let checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.checked = false; // Default false, artinya belum di-qada
                    checkbox.dataset.prayerName = salat.nama;

                    let label = document.createElement('label');
                    label.classList.add('ml-2');
                    label.innerText = 'Sholat ' + salat.nama;
                    label.classList.add('text-red-500');

                    let div = document.createElement('div');
                    div.classList.add('flex', 'items-center', 'mb-2');
                    div.appendChild(checkbox);
                    div.appendChild(label);
                    qadaList.appendChild(div);

                    // Event listener untuk checkbox dengan konfirmasi
                    checkbox.addEventListener('change', function () {
                        if (this.checked) {
                            const userConfirmed = confirm(`Apakah kamu benar-benar sudah menqada sholat ${salat.nama}?`);

                            if (userConfirmed) {
                                let prayerName = this.dataset.prayerName;

                                // Mengirim permintaan ke backend untuk memperbarui atau menghapus salat qada
                                fetch(`/menstruation/update-qada-salat/${menstruationId}`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify({
                                        prayer_name: prayerName
                                    })
                                })
                                .then(response => response.json())
                                .then(result => {
                                    console.log(result); // Debug log untuk memastikan respons diterima
                                    if (result.message) {
                                        alert(result.message);
                                        this.parentElement.remove(); // Hapus elemen checkbox dari UI
                                        
                                        // Tutup modal setelah penghapusan berhasil
                                        closeModal();

                                        // Update tampilan status qada salat di tabel
                                        const qadaList = document.getElementById('qada-salat-list');
                                        if (qadaList.children.length === 0) {
                                            // Jika tidak ada lagi checkbox tersisa (semua salat sudah di-qada)
                                            const qadaSalatCell = document.querySelector(`#qada-salat-${menstruationId}`);
                                            if (qadaSalatCell) {
                                                qadaSalatCell.textContent = 'Tidak';
                                                qadaSalatCell.classList.remove('bg-red-500');
                                                qadaSalatCell.classList.add('bg-yellow-500');
                                            }
                                        }
                                    }
                                })

                                .catch(error => {
                                    console.error('Error:', error);
                                });
                            } else {
                                // Jika pengguna tidak mengonfirmasi, maka checkbox tetap tidak dicentang
                                this.checked = false;
                            }
                        }
                    });
                });
            } else {
                let noQadaText = document.createElement('p');
                noQadaText.innerText = '-';
                qadaList.appendChild(noQadaText);
            }

            // Tampilkan modal
            const modal = document.getElementById('detail-modal');
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function closeModal() {
    const modal = document.getElementById('detail-modal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
}
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