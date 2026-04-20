<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PinjamPPLG - Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#f8fafc] antialiased">
    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-80 bg-[#0f172a] text-slate-400 flex-shrink-0 hidden md:flex flex-col border-r border-slate-800">
            <div class="p-8 flex items-center space-x-4">
                <div class="bg-gradient-to-br from-white to-slate-100 p-2.5 rounded-2xl shadow-lg shadow-white/5">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo" class="w-8 h-8 object-contain">
                </div>
                <div>
                    <h1 class="text-white font-extrabold text-xl tracking-tight leading-none">Pinjam<span class="text-blue-500">PPLG</span></h1>
                    <p class="text-[9px] text-slate-500 uppercase tracking-[0.3em] mt-1.5 font-bold">Skenic Inventory</p>
                </div>
            </div>

            <nav class="flex-1 px-6 space-y-8 overflow-y-auto custom-scrollbar pb-6">
                
                <div class="space-y-2">
                    <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.2em] px-3">Ringkasan</p>
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : (Auth::user()->role === 'petugas' ? route('petugas.dashboard') : route('dashboard')) }}" 
                        class="nav-item {{ request()->routeIs('*.dashboard') || request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-xl shadow-blue-600/20 active-glow' : 'hover:text-white' }}">
                        <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                        <span class="font-bold text-sm">Dashboard</span>
                    </a>
                </div>

                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'petugas')
                <div class="space-y-2">
                    <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.2em] px-3">Kelola Data</p>
                    @if(Auth::user()->role === 'admin')
                    <a href="{{ route('petugas.index') }}" class="nav-item {{ request()->routeIs('petugas.*') ? 'bg-white/10 text-white' : 'hover:text-white' }}">
                        <i data-lucide="shield-check" class="w-5 h-5 mr-3"></i>
                        <span class="font-semibold text-sm">Kelola Petugas</span>
                    </a>
                    @endif
                    <a href="{{ route('kategori.index') }}" class="nav-item {{ request()->routeIs('kategori.*') ? 'bg-white/10 text-white' : 'hover:text-white' }}">
                        <i data-lucide="layers" class="w-5 h-5 mr-3"></i>
                        <span class="font-semibold text-sm">Kelola Kategori</span>
                    </a>
                    <a href="{{ route('admin.barang.index') }}" class="nav-item {{ request()->routeIs('barang.*') ? 'bg-white/10 text-white' : 'hover:text-white' }}">
                        <i data-lucide="package-search" class="w-5 h-5 mr-3"></i>
                        <span class="font-semibold text-sm">Kelola Barang</span>
                    </a>
                </div>

                <div class="space-y-2">
                    <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.2em] px-3">Operasional</p>
                    <a href="{{ route('peminjaman.index') }}" class="nav-item {{ request()->routeIs('peminjaman.*') ? 'bg-white/10 text-white' : 'hover:text-white' }}">
                        <i data-lucide="shopping-cart" class="w-5 h-5 mr-3 text-rose-400"></i>
                        <span class="font-semibold text-sm">Data Peminjaman</span>
                    </a>
                    <a href="{{ route('pengembalian.index') }}" class="nav-item {{ request()->routeIs('pengembalian.*') ? 'bg-white/10 text-white' : 'hover:text-white' }}">
                        <i data-lucide="check-circle-2" class="w-5 h-5 mr-3 text-emerald-400"></i>
                        <span class="font-semibold text-sm">Data Pengembalian</span>
                    </a>
                    <a href="{{ route('laporan.index') }}" class="flex items-center w-full px-4 py-2 text-sm font-medium text-gray-600 transition-all duration-200 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 group">
                        <div class="flex items-center justify-center w-8 h-8 mr-3 bg-emerald-100 text-emerald-600 rounded-lg group-hover:bg-emerald-600 group-hover:text-white transition-all">
                            <i class="fa-solid fa-file-pdf"></i>
                        </div>
                        <span>Rekap Laporan</span>
                    </a>
                </div>

                
                @endif

                @if(Auth::user()->role === 'siswa')
                <div class="space-y-2">
                    <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.2em] px-3">Menu Siswa</p>
                    <a href="{{ route('pinjam.alat') }}" class="nav-item {{ request()->routeIs('pinjam.alat') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-xl' : 'hover:text-white' }}">
                        <i data-lucide="plus-square" class="w-5 h-5 mr-3"></i>
                        <span class="font-bold text-sm text-white">Katalog Barang</span>
                    </a>
                    <a href="{{ route('siswa.riwayat') }}" class="nav-item {{ request()->routeIs('siswa.riwayat') ? 'bg-white/10 text-white' : 'hover:text-white' }}">
                        <i data-lucide="clock-3" class="w-5 h-5 mr-3 text-blue-400"></i>
                        <span class="font-semibold text-sm">Riwayat Peminjaman Saya</span>
                    </a>
                </div>
                @endif
            </nav>

            <div class="p-6 bg-slate-900/50 border-t border-slate-800">
                <div class="bg-slate-800/50 p-4 rounded-2xl flex items-center mb-4 border border-slate-700/50">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-500 to-indigo-600 flex items-center justify-center text-white font-black shadow-lg">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="ml-3 overflow-hidden">
                        <p class="text-xs font-black text-white truncate">{{ Auth::user()->name }}</p>
                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">{{ Auth::user()->role }}</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center p-3.5 bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white rounded-2xl transition-all duration-300 font-bold text-[11px] uppercase tracking-[0.1em]">
                        <i data-lucide="power" class="w-4 h-4 mr-2.5"></i>
                        Log Out
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="h-20 glass-header border-b border-slate-200 flex items-center justify-between px-10">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-50 p-2 rounded-lg md:hidden">
                        <i data-lucide="menu" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <h2 class="text-xl font-black text-slate-800 tracking-tight">{{ $header ?? 'Dashboard' }}</h2>
                </div>
                
                <div class="flex items-center space-x-6">
                    <div class="hidden sm:flex flex-col text-right">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu Sekarang</span>
                        <span class="text-xs font-bold text-slate-700">{{ now()->translatedFormat('l, d F Y') }}</span>
                    </div>
                    <div class="h-10 w-[1px] bg-slate-200"></div>
                    <button class="relative p-2.5 bg-slate-50 text-slate-500 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-colors group">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-rose-500 rounded-full border-2 border-white"></span>
                    </button>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-10 animate-fade-in custom-scrollbar">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>