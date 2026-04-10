<x-app-layout>
    <x-slot name="header">Ringkasan Statistik</x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
    <div class="flex justify-between items-start">
        <div>
            <p class="text-sm text-gray-400 font-medium">Total Barang</p>
            <h3 class="text-2xl font-bold text-[#272b34] mt-1">{{ $jumlahBarang }}</h3>
        </div>
        <div class="p-3 bg-blue-50 rounded-2xl text-blue-500">
            <i data-lucide="package" class="w-6 h-6"></i>
        </div>
    </div>
    
    <div class="mt-4 pt-4 border-t border-gray-50">
        <a href="{{ route('barang.index') }}" class="text-blue-500 text-[10px] font-extrabold hover:underline flex items-center">
            LIHAT DETAIL <i data-lucide="chevron-right" class="w-3 h-3 ml-1"></i>
        </a>
    </div>
</div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-50 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Peminjaman</p>
                    <h3 class="text-3xl font-extrabold text-gray-800">{{ $jumlahPeminjaman ?? 0 }}</h3>
                </div>
                <div class="bg-green-50 p-4 rounded-2xl">
                    <i data-lucide="refresh-cw" class="text-green-600 w-8 h-8"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-green-600 font-semibold uppercase tracking-wider">
                <span>Proses Transaksi</span>
                <i data-lucide="chevron-right" class="w-4 h-4 ml-1"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-50 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Siswa</p>
                    <h3 class="text-3xl font-extrabold text-gray-800">{{ $jumlahSiswa }}</h3>
                </div>
                <div class="bg-purple-50 p-4 rounded-2xl">
                    <i data-lucide="graduation-cap" class="text-purple-600 w-8 h-8"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-purple-600 font-semibold uppercase tracking-wider">
                <span>Daftar Siswa</span>
                <i data-lucide="chevron-right" class="w-4 h-4 ml-1"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-8 border border-gray-50 shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <h4 class="text-lg font-bold text-gray-800">Aktivitas Peminjaman Terakhir</h4>
            <button class="text-sm text-blue-600 font-bold hover:underline">Lihat Semua</button>
        </div>
        <div class="text-center py-12 text-gray-400">
            <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-4 opacity-20"></i>
            <p>Belum ada data peminjaman yang tercatat hari ini.</p>
        </div>
    </div>
</x-app-layout>