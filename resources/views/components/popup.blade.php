<div id="popupModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-sm text-center">
        <div class="text-green-500 text-4xl mb-4">
            <i class="fas fa-check-circle"></i>
        </div>
        <p class="text-gray-800 font-semibold mb-6" id="popupMessage">
            <!-- Pesan akan diisi secara dinamis -->
        </p>
        <div class="flex flex-col gap-4">
            <!-- Tombol Lihat Riwayat -->
            <button onclick="window.location.href='{{ route('riwayat.menstruasi') }}'" 
                    class="bg-[#5F7E78] text-white px-4 py-2 rounded-md hover:bg-[#4e6863]">
                Lihat Riwayat
            </button>
            <!-- Tombol Kembali ke Beranda -->
            <button onclick="window.location.href='{{ route('home') }}'" 
                    class="text-gray-600 px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md">
                Kembali ke Beranda
            </button>
        </div>
    </div>
</div>
