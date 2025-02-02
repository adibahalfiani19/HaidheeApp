<!-- Login Modal Popup -->
<style>
    /* Default Modal */
    #loginModal > div {
        width: 36rem; /* Lebar normal untuk layar besar */
        padding: 2rem; /* Padding default */
        font-size: 1rem; /* Font size default */
    }
    
    /* Untuk layar sangat kecil (max-width: 480px) */
    @media (max-width: 640px) {
        #loginModal > div {
            width: 90%; /* Lebar modal lebih kecil */
            max-width: 20rem; /* Batas maksimal lebih kecil */
            padding: 1.5rem; /* Padding lebih kecil */
            font-size: 0.875rem; /* Ukuran font lebih kecil */
        }
    
        #loginModal h2 {
            font-size: 1.5rem; /* Header lebih kecil */
        }
    
        #loginModal h3 {
            font-size: 1.25rem; /* Sub-header lebih kecil */
        }
    
        #loginModal p,
        #loginModal label {
            font-size: 0.875rem; /* Ukuran teks lebih kecil */
        }
    
        #loginModal input,
        #loginModal button {
            padding: 0.75rem; /* Padding input dan button lebih kecil */
        }
    
        #loginModal button {
            font-size: 0.875rem; /* Ukuran font button lebih kecil */
        }

        /* Responsif untuk layar kecil */
        #toggleLoginPassword {
            right: 0.75rem; /* Kurangi jarak untuk layar kecil */
            top: 50%;
            transform: translateY(-50%);
        }
    }

</style>

<div id="loginModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-[1100]">
    <div class="bg-white rounded-3xl shadow-lg w-[36rem] p-8 relative">
        <!-- Tombol Tutup -->
        <button id="closeLogin" class="absolute top-4 right-4 text-gray-600 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-400 rounded-full">X</button>
        
        <!-- Header Modal -->
        <h2 class="text-center text-3xl font-bold text-[#5F7E78] mb-4">
            Haidh<span class="text-[#D4BE83]">ee</span>
        </h2>
        <h3 class="text-center text-xl font-semibold mb-2">Selamat datang!</h3>
        <p class="text-center text-gray-500 mb-6">Masukkan email dan password untuk mengakses akun Anda.</p>

        <!-- Form Login -->
        <form id="loginForm" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                <input type="email" name="email" id="email" required class="w-full p-3 border rounded-xl bg-[#F1EAD7] focus:outline-none focus:ring-2 focus:ring-[#5F7E78]" placeholder="Masukkan Email Anda" 
                value="{{ old('email') }}">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="loginPassword" required class="w-full p-3 border rounded-xl bg-[#F1EAD7] focus:outline-none focus:ring-2 focus:ring-[#5F7E78]" placeholder="Masukkan Password Anda">
                    <button type="button" id="toggleLoginPassword" class="absolute right-3 top-3 text-gray-500 focus:outline-none">
                        <i class="fas fa-eye-slash"></i>
                    </button>
                </div>
            </div>
            <a href="#" class="text-sm text-[#5F7E78] hover:underline block text-right mb-6">Lupa Password?</a>
            <button type="submit" class="bg-[#5F7E78] text-white w-full py-3 rounded-xl font-semibold hover:bg-[#4e6863] transition duration-300 mb-2">Masuk</button>
        </form>

        <!-- Divider dengan tulisan -->
        <div class="flex items-center my-4">
            <div class="flex-grow border-t border-gray-300"></div>
            <span class="px-3 text-gray-500 text-sm font-semibold">atau</span>
            <div class="flex-grow border-t border-gray-300"></div>
        </div>

        <form action="{{ route('google.login') }}" method="GET">
            <button type="submit" class="bg-[#DB4437] text-white w-full py-3 rounded-xl font-semibold hover:bg-[#c23b2e] transition duration-300">
                <i class="fab fa-google mr-2"></i> Masuk dengan Google
            </button>
        </form>
        
        <!-- Link ke Modal Register -->
        <p class="text-center text-gray-600 mt-6">Belum punya akun? <a href="#" id="showRegisterModalFromLogin" class="text-[#5F7E78] font-semibold hover:underline">Daftar Sekarang</a></p>
    </div>
</div>

@if (session('modal') === 'login')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            alert("{{ session('alertMessage') }}");
            document.getElementById('loginModal').classList.remove('hidden'); // Buka modal login
            document.getElementById('registerModal').classList.add('hidden'); // Tutup modal register
        });
    </script>
@endif