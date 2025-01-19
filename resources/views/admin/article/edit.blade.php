<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Artikel (Admin) - Haidhee</title>
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

        .article-form-container {
            background-color: #E3EFEC; /* Warna latar belakang */
            padding: 40px;
            border-radius: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-control,
        .form-control-file {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 12px;
            margin-bottom: 10px;
        }

        .btn-upload {
            background-color: #5F7E78; /* Match button color */
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            
        }

        .btn-upload:hover {
            background-color: #4e6863;
        }
        /* Mengatur layout input di samping label untuk layar besar */
        @media (min-width: 768px) {
            .form-group {
                display: flex;
                align-items: center;
            }

            .form-group label {
                flex: 1; /* Memberikan ruang yang cukup pada label */
                margin-bottom: 0; /* Menghapus margin bawah */
            }

            .form-group .form-control,
            .form-group .form-control-file {
                flex: 4; /* Memperbesar input */
                margin-left: 10px; /* Menambahkan sedikit jarak antara label dan input */
            }
            .form-submit-container {
                display: flex;
                justify-content: flex-end;
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

<div class="container mx-auto mt-14 mb-14 px-6 md:px-14">
    <h1 class="text-2xl font-bold mb-4">Edit Artikel</h1>
    <div class="article-form-container">
        <form action="{{ route('article.update', $article->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="title">Judul</label>
                <input type="text" name="title" class="form-control" value="{{ $article->title }}" required>
            </div>
            
            <div class="form-group">
                <label for="author">Penulis</label>
                <input type="text" name="author" class="form-control" value="{{ $article->author }}" required>
            </div>
            
            <div class="form-group">
                <label for="published_date">Tanggal Publikasi</label>
                <input type="date" name="published_date" class="form-control" value="{{ $article->published_date }}" required>
            </div>
            
            <div class="form-group">
                <label for="content">Konten</label>
                <textarea name="content" class="form-control" rows="5" required>{{ $article->content }}</textarea>
            </div>
            
            <div class="form-group">
                <label>Gambar Lama</label>
                <div class="form-control" id="fileLabel">
                    @if($article->image)
                        {{ basename($article->image) }}
                    @else
                        Belum ada file yang dipilih
                    @endif
                </div>
            </div>            
            
            <div class="form-group">
                <label for="image">Gambar Baru (Opsional)</label>
                <input type="file" name="image" class="form-control-file" id="imageInput">
            </div>                   

            <div class="form-submit-container">
                <button type="submit" class="btn-upload">Simpan</button>
            </div>  
        </form>
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

    document.getElementById('imageInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const fileLabel = document.getElementById('fileLabel');

        if (file) {
            fileLabel.textContent = file.name;
        } else {
            fileLabel.textContent = 'Belum ada file yang dipilih';
        }
    });
</script>
</body>
</html>