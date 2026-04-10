<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PinjamPPLG - Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script> </head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-72 bg-[#0f172a] text-gray-300 flex-shrink-0 hidden md:flex flex-col">
            <div class="p-8 flex items-center space-x-3">
                <div class="bg-white p-2 rounded-lg shadow-sm">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo" class="w-8 h-8 object-contain">
                </div>
                <div>
                    <h1 class="text-white font-bold text-xl tracking-tight">Pinjam<span class="text-blue-500">PPLG</span></h1>
                    <p class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Inventory System</p>
                </div>
            </div>

            <nav class="flex-1 px-6 space-y-2 overflow-y-auto">
    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-4 px-2">Main Menu</p>
    
    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : (Auth::user()->role === 'petugas' ? route('petugas.dashboard') : route('dashboard')) }}" 
        class="flex items-center p-3 rounded-xl {{ request()->routeIs('*.dashboard') || request()->routeIs('dashboard') ? 'bg-gradient-to-r from-[#2f77dd] to-[#7c4dc2] text-white shadow-lg shadow-indigo-500/20' : 'hover:bg-white/5 transition text-gray-400 group' }}">
        <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
        <span class="font-medium">Dashboard</span>
    </a>

    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'petugas')
        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mt-8 mb-4 px-2">Master Data</p>

        @if(Auth::user()->role === 'admin')
        <a href="{{ route('petugas.index') }}" 
            class="flex items-center p-3 rounded-xl {{ request()->routeIs('petugas.index') ? 'bg-gradient-to-r from-[#2f77dd] to-[#7c4dc2] text-white shadow-lg shadow-indigo-500/20' : 'hover:bg-white/5 transition text-gray-400 group' }}">
            <i data-lucide="users" class="w-5 h-5 mr-3"></i>
            <span class="font-medium">Data Petugas</span>
        </a>
        @endif

        <a href="{{ route('kategori.index') }}" 
            class="flex items-center p-3 rounded-xl {{ request()->routeIs('kategori.index') ? 'bg-gradient-to-r from-[#2f77dd] to-[#7c4dc2] text-white shadow-lg shadow-indigo-500/20' : 'hover:bg-white/5 transition text-gray-400 group' }}">
            <i data-lucide="package" class="w-5 h-5 mr-3"></i>
            <span class="font-medium">Data Kategori</span>
        </a>

        <a href="{{ route('barang.index') }}" 
            class="flex items-center p-3 rounded-xl {{ request()->routeIs('barang.index') ? 'bg-gradient-to-r from-[#2f77dd] to-[#7c4dc2] text-white shadow-lg shadow-indigo-500/20' : 'hover:bg-white/5 transition text-gray-400 group' }}">
            <i data-lucide="package" class="w-5 h-5 mr-3"></i>
            <span class="font-medium">Data Barang</span>
        </a>

        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mt-8 mb-4 px-2">Activity</p>

        <a href="{{ route('peminjaman.index') }}" 
            class="flex items-center p-3 rounded-xl {{ request()->routeIs('peminjaman.index') ? 'bg-gradient-to-r from-[#2f77dd] to-[#7c4dc2] text-white shadow-lg shadow-indigo-500/20' : 'hover:bg-white/5 transition text-gray-400 group' }}">
            <i data-lucide="clipboard-list" class="w-5 h-5 mr-3"></i>
            <span class="font-medium">Data Peminjaman</span>
        </a>

        <a href="{{ route('pengembalian.index') }}" 
    class="flex items-center p-3 rounded-xl {{ request()->routeIs('pengembalian.index') ? 'bg-gradient-to-r from-[#2f77dd] to-[#7c4dc2] text-white shadow-lg shadow-indigo-500/20' : 'hover:bg-white/5 transition text-gray-400 group' }}">
        <i data-lucide="package-check" class="w-5 h-5 mr-3"></i>
        <span class="font-medium">Verifikasi Kembali</span>
    </a>
    @endif

    @if(Auth::user()->role === 'siswa')
        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mt-8 mb-4 px-2">Layanan Siswa</p>
        
        <a href="{{ route('pinjam.alat') }}" 
            class="flex items-center p-3 rounded-xl {{ request()->routeIs('pinjam.alat') ? 'bg-gradient-to-r from-[#2f77dd] to-[#7c4dc2] text-white shadow-lg shadow-indigo-500/20' : 'hover:bg-white/5 transition text-gray-400 group' }}">
            <i data-lucide="clipboard-list" class="w-5 h-5 mr-3"></i>
            <span class="font-medium">Pinjam Alat</span>
        </a>

                <a href="{{ route('siswa.riwayat') }}" 
            class="flex items-center p-3 rounded-xl {{ request()->routeIs('siswa.riwayat') ? 'bg-gradient-to-r from-[#2f77dd] to-[#7c4dc2] text-white shadow-lg shadow-indigo-500/20' : 'hover:bg-white/5 transition text-gray-400 group' }}">
            <i data-lucide="history" class="w-5 h-5 mr-3"></i>
            <span class="font-medium">Riwayat Pinjam</span>
        </a>
    @endif
</nav>

            <div class="p-6 border-t border-slate-800">
                <div class="flex items-center mb-4 px-2">
                    <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold mr-3">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full p-3 text-red-400 hover:bg-red-900/20 rounded-xl transition">
                        <i data-lucide="log-out" class="w-5 h-5 mr-3"></i>
                        <span class="font-medium text-sm">Keluar Sistem</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-8">
                <h2 class="text-xl font-bold text-gray-800">{{ $header ?? 'Dashboard' }}</h2>
                <div class="flex items-center space-x-4">
                    <button class="p-2 text-gray-400 hover:bg-gray-50 rounded-full">
                        <i data-lucide="bell" class="w-6 h-6"></i>
                    </button>
                    <div class="h-8 w-[1px] bg-gray-200 mx-2"></div>
                    <span class="text-sm font-medium text-gray-600">{{ now()->format('d M Y') }}</span>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        lucide.createIcons(); // Inisialisasi Icon Lucide
    </script>
</body>
</html>