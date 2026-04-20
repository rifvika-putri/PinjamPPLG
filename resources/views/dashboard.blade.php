<x-app-layout>
    <div class="py-10 bg-[#f8fafc] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight italic font-serif">Pinjam<span class="text-blue-600">PPLG</span> Dashboard</h1>
                    <p class="text-slate-500 text-sm">Selamat datang kembali, <span class="font-bold text-slate-700">{{ Auth::user()->name }}</span></p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ url('/katalog') }}" class="flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-2xl text-xs font-bold shadow-lg shadow-blue-100 hover:bg-blue-700 transition">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i> Pinjam Barang
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                
                <div class="lg:col-span-1 space-y-4">
                    <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4 px-2">Main Menu</p>
                        <nav class="space-y-1">
                            <a href="#" class="flex items-center gap-3 bg-blue-50 text-blue-600 px-4 py-3 rounded-2xl font-bold text-sm">
                                <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Ringkasan
                            </a>
                            <a href="#" class="flex items-center gap-3 text-slate-500 hover:bg-slate-50 px-4 py-3 rounded-2xl font-semibold text-sm transition">
                                <i data-lucide="history" class="w-5 h-5"></i> Riwayat Pinjam
                            </a>
                            <a href="#" class="flex items-center gap-3 text-slate-500 hover:bg-slate-50 px-4 py-3 rounded-2xl font-semibold text-sm transition">
                                <i data-lucide="bell" class="w-5 h-5"></i> Notifikasi
                            </a>
                            <a href="#" class="flex items-center gap-3 text-slate-500 hover:bg-slate-50 px-4 py-3 rounded-2xl font-semibold text-sm transition">
                                <i data-lucide="settings" class="w-5 h-5"></i> Pengaturan Akun
                            </a>
                        </nav>
                    </div>

                    <div class="bg-rose-50 rounded-[2rem] p-6 border border-rose-100">
                        <div class="flex items-center gap-3 text-rose-600 mb-2">
                            <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                            <span class="font-bold text-sm">Informasi Denda</span>
                        </div>
                        <p class="text-rose-400 text-xs leading-relaxed">Pastikan mengembalikan barang tepat waktu untuk menghindari denda administratif.</p>
                    </div>
                </div>

                <div class="lg:col-span-3 space-y-6">
                    
                    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100">
                        <h3 class="font-bold text-slate-800 mb-6">Status Peminjaman Aktif</h3>
                        
                        <div class="flex flex-col items-center py-10 text-center">
                            <div class="bg-slate-50 p-6 rounded-full mb-4">
                                <i data-lucide="package-search" class="w-10 h-10 text-slate-300"></i>
                            </div>
                            <p class="text-slate-400 text-sm font-medium">Kamu tidak memiliki pinjaman aktif saat ini.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-slate-900 rounded-[2rem] p-8 text-white flex justify-between items-center overflow-hidden relative">
                            <div class="relative z-10">
                                <h4 class="font-bold text-lg leading-tight">Cek Katalog<br>Alat PPLG</h4>
                                <a href="{{ url('/katalog') }}" class="text-blue-400 text-xs font-bold mt-4 inline-block hover:underline text-[10px] uppercase tracking-widest">Lihat Semua →</a>
                            </div>
                            <i data-lucide="laptop" class="w-24 h-24 absolute -right-4 -bottom-4 opacity-20 rotate-12"></i>
                        </div>
                        <div class="bg-blue-600 rounded-[2rem] p-8 text-white flex justify-between items-center overflow-hidden relative">
                            <div class="relative z-10">
                                <h4 class="font-bold text-lg leading-tight">Butuh Bantuan<br>Admin?</h4>
                                <a href="#" class="text-blue-200 text-xs font-bold mt-4 inline-block hover:underline text-[10px] uppercase tracking-widest">Hubungi Kami →</a>
                            </div>
                            <i data-lucide="help-circle" class="w-24 h-24 absolute -right-4 -bottom-4 opacity-20 -rotate-12"></i>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</x-app-layout>