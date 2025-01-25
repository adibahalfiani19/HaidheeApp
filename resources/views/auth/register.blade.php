<!-- Register Modal Popup -->
<div id="registerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-[1100]">
    <div class="bg-white rounded-3xl shadow-lg w-[36rem] p-8 relative">
        <!-- Tombol Tutup -->
        <button id="closeRegister" class="absolute top-4 right-4 text-gray-600 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-400 rounded-full">X</button>
        
        <!-- Header Modal -->
        <h2 class="text-center text-3xl font-bold text-[#5F7E78] mb-4">
            Haidh<span class="text-[#D4BE83]">ee</span>
        </h2>
        <h3 class="text-center text-xl font-semibold mb-2">Buat akun Anda</h3>
        <p class="text-center text-gray-500 mb-6">Daftar sekarang untuk mengakses semua fitur dan konten kami.</p>
        
        <!-- Form Register -->
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Nama Lengkap</label>
                <input type="text" name="name" id="name" required value="{{ old('name') }}" required class="w-full p-3 border rounded-xl bg-[#F1EAD7] focus:outline-none focus:ring-2 focus:ring-[#5F7E78]" placeholder="Masukkan Nama Lengkap Anda">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}" required class="w-full p-3 border rounded-xl bg-[#F1EAD7] focus:outline-none focus:ring-2 focus:ring-[#5F7E78]" placeholder="Masukkan Email Anda">
            </div>
            <div class="mb-8">
                <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="registerPassword" required class="w-full p-3 border rounded-xl bg-[#F1EAD7] focus:outline-none focus:ring-2 focus:ring-[#5F7E78]" placeholder="Masukkan Password Anda">
                    <button type="button" id="toggleRegisterPassword" class="absolute right-3 top-3 text-gray-500 focus:outline-none">
                        <i class="fas fa-eye-slash"></i>
                    </button>
                </div>     
                <!-- Password Requirements -->
                <div id="passwordRequirements" class="hidden mt-2 text-sm text-gray-500">
                    <div id="minLength" class="flex items-center">
                        <span class="icon text-red-500 mr-2">○</span>
                        <span class="text">6 characters</span>
                    </div>
                    <div id="uppercase" class="flex items-center">
                        <span class="icon text-red-500 mr-2">○</span>
                        <span class="text">1 uppercase</span>
                    </div>
                    <div id="number" class="flex items-center">
                        <span class="icon text-red-500 mr-2">○</span>
                        <span class="text">1 number</span>
                    </div>
                </div>                     
            </div>                        
            
            {{-- <div class="mb-4">
                <label for="whatsapp_number" class="block text-gray-700 font-bold mb-2">Nomor WhatsApp</label>
                <input type="text" name="whatsapp_number" id="whatsapp_number" required class="w-full p-3 border rounded-xl bg-[#F1EAD7] focus:outline-none focus:ring-2 focus:ring-[#5F7E78]" placeholder="Masukkan Nomor WhatsApp Anda">
            </div> --}}
            
            <button type="submit" class="bg-[#5F7E78] text-white w-full py-3 rounded-xl font-semibold hover:bg-[#4e6863] transition duration-300">Daftar</button>
        </form>

        <!-- Divider dengan tulisan -->
        <div class="flex items-center my-4">
            <div class="flex-grow border-t border-gray-300"></div>
            <span class="px-3 text-gray-500 text-sm font-semibold">atau</span>
            <div class="flex-grow border-t border-gray-300"></div>
        </div>

        <form action="{{ route('google.login') }}" method="GET">
            <button type="submit" class="bg-[#DB4437] text-white w-full py-3 rounded-xl font-semibold hover:bg-[#c23b2e] transition duration-300">
                <i class="fab fa-google mr-2"></i> Daftar dengan Google
            </button>
        </form>
        
        <!-- Link ke Modal Login -->
        <p class="text-center text-gray-600 mt-6">Sudah punya akun? <a href="#" id="showLoginModalFromRegister" class="text-[#5F7E78] font-semibold hover:underline">Masuk Sekarang</a></p>
    </div>
</div>

@if (session('registrationSuccess')) 
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            alert("{{ session('alertMessage') }}");
            document.getElementById('loginModal').classList.remove('hidden'); // Buka modal login
            document.getElementById('registerModal').classList.add('hidden'); // Tutup modal register
        });
    </script>
@endif

@if ($errors->any())
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            alert("{{ $errors->first() }}");
            document.getElementById('registerModal').classList.remove('hidden'); // Buka modal login
            document.getElementById('loginModal').classList.add('hidden'); // Tutup modal register
        });
    </script>
@endif

<script>
document.addEventListener("DOMContentLoaded", function () {
    const passwordInput = document.getElementById("registerPassword");
    const requirements = document.getElementById("passwordRequirements");
    const minLength = document.getElementById("minLength");
    const uppercase = document.getElementById("uppercase");
    const number = document.getElementById("number");

    // Tampilkan password requirements saat input difokuskan
    passwordInput.addEventListener("focus", function () {
        requirements.classList.remove("hidden");
    });

    // Sembunyikan password requirements saat input kehilangan fokus
    passwordInput.addEventListener("blur", function () {
        if (passwordInput.value === "") {
            requirements.classList.add("hidden");
        }
    });

    // Validasi password secara real-time
    passwordInput.addEventListener("input", function () {
        const value = passwordInput.value;

        // Validasi dan perbarui indikator
        updateIndicator(minLength, value.length >= 6);
        updateIndicator(uppercase, /[A-Z]/.test(value));
        updateIndicator(number, /\d/.test(value));
    });

    // Fungsi untuk memperbarui indikator
    function updateIndicator(element, isValid) {
        const icon = element.querySelector("span.icon");
        const text = element.querySelector("span.text");
        if (isValid) {
            icon.textContent = "✓"; // Centang jika valid
            icon.classList.remove("text-red-500");
            icon.classList.add("text-green-500");
        } else {
            icon.textContent = "○"; // Bulat jika tidak valid
            icon.classList.remove("text-green-500");
            icon.classList.add("text-red-500");
        }
    }
});

</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Mengambil elemen-elemen yang diperlukan
    const loginModal = document.getElementById('loginModal');
    const registerModal = document.getElementById('registerModal');

    // Tombol Login dan Register dari Sidebar dan Header
    const showLoginBtn = document.getElementById('loginButton');
    const showRegisterBtn = document.getElementById('registerButton');
    const showLoginBtnSidebar = document.getElementById('loginButtonSidebar');
    const showRegisterBtnSidebar = document.getElementById('registerButtonSidebar');

    // Tombol untuk menutup modal
    const closeLoginBtn = document.getElementById('closeLogin');
    const closeRegisterBtn = document.getElementById('closeRegister');

    // Tombol untuk mengganti modal (login <-> register)
    const showRegisterModalFromLogin = document.getElementById('showRegisterModalFromLogin');
    const showLoginModalFromRegister = document.getElementById('showLoginModalFromRegister');

    // Tampilkan modal login
    if (showLoginBtn) {
        showLoginBtn.addEventListener('click', function (e) {
            e.preventDefault();
            loginModal.classList.remove('hidden');
        });
    }
    if (showLoginBtnSidebar) {
        showLoginBtnSidebar.addEventListener('click', function (e) {
            e.preventDefault();
            loginModal.classList.remove('hidden');
        });
    }

    // Tampilkan modal register
    if (showRegisterBtn) {
        showRegisterBtn.addEventListener('click', function (e) {
            e.preventDefault();
            registerModal.classList.remove('hidden');
        });
    }
    if (showRegisterBtnSidebar) {
        showRegisterBtnSidebar.addEventListener('click', function (e) {
            e.preventDefault();
            registerModal.classList.remove('hidden');
        });
    }

    // Tutup modal login
    if (closeLoginBtn) {
        closeLoginBtn.addEventListener('click', function () {
            loginModal.classList.add('hidden');
        });
    }

    // Tutup modal register
    if (closeRegisterBtn) {
        closeRegisterBtn.addEventListener('click', function () {
            registerModal.classList.add('hidden');
        });
    }

    // Tampilkan modal register dari modal login
    if (showRegisterModalFromLogin) {
        showRegisterModalFromLogin.addEventListener('click', function (e) {
            e.preventDefault();
            loginModal.classList.add('hidden');
            registerModal.classList.remove('hidden');
        });
    }

    // Tampilkan modal login dari modal register
    if (showLoginModalFromRegister) {
        showLoginModalFromRegister.addEventListener('click', function (e) {
            e.preventDefault();
            registerModal.classList.add('hidden');
            loginModal.classList.remove('hidden');
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    // Login Password Show/Hide Toggle
    const toggleLoginPassword = document.getElementById('toggleLoginPassword');
    const loginPassword = document.getElementById('loginPassword');

    if (toggleLoginPassword) {
        toggleLoginPassword.addEventListener('click', function () {
            // Toggle between password and text type
            const type = loginPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            loginPassword.setAttribute('type', type);

            // Toggle icon class between eye and eye-slash
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }

    // Register Password Show/Hide Toggle
    const toggleRegisterPassword = document.getElementById('toggleRegisterPassword');
    const registerPassword = document.getElementById('registerPassword');

    if (toggleRegisterPassword) {
        toggleRegisterPassword.addEventListener('click', function () {
            // Toggle between password and text type
            const type = registerPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            registerPassword.setAttribute('type', type);

            // Toggle icon class between eye and eye-slash
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }
});
</script>
