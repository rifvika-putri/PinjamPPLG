<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PinjamPPLG - SMKN 1 Ciomas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            /* Background Gradasi Mewah */
            background: radial-gradient(at 0% 0%, rgba(79, 70, 229, 0.15) 0, transparent 50%), 
                        radial-gradient(at 100% 0%, rgba(45, 212, 191, 0.1) 0, transparent 50%),
                        radial-gradient(at 100% 100%, rgba(244, 63, 94, 0.05) 0, transparent 50%),
                        #f8fafc;
        }

        /* Animasi Mengambang untuk Laptop */
        .floating {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(1deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }

        /* Efek Shine pada Tombol */
        .btn-shine {
            position: relative;
            overflow: hidden;
        }
        .btn-shine::after {
            content: '';
            position: absolute;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
            transform: rotate(45deg);
            transition: 0.5s;
        }
        .btn-shine:hover::after {
            left: 120%;
        }
    </style>
</head>
<body class="min-h-screen overflow-x-hidden">

    <nav class="fixed top-0 w-full z-50 px-8 py-6 flex justify-between items-center bg-white/30 backdrop-blur-md">
        <div class="text-xl font-black text-indigo-900 tracking-tighter">pinjam<span class="text-indigo-600">PPLG</span></div>
        <div class="flex items-center gap-6">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-6 py-2 bg-indigo-600 text-white text-xs font-bold rounded-xl shadow-lg hover:scale-105 transition-transform">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 hover:text-indigo-600 transition-colors">Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-shine px-6 py-3 bg-slate-900 text-white text-xs font-bold rounded-xl shadow-xl hover:-translate-y-1 active:scale-95 transition-all">
                            Daftar Akun
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <main class="pt-32 pb-20 px-8 flex flex-col items-center">
        <div class="text-center space-y-6 max-w-3xl mb-16">
            <h1 class="text-5xl lg:text-6xl font-black text-slate-900 leading-tight tracking-tight">
                Peminjaman Barang <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-emerald-500">SMKN 1 Ciomas</span>
            </h1>
            <p class="text-lg text-slate-500 font-medium">
                Satu platform untuk semua kebutuhan praktik jurusan PPLG. <br class="hidden md:block">
                Cepat, Digital, dan Terdata Otomatis.
            </p>
        </div>

        <div class="relative w-full max-w-4xl mx-auto floating">
            <div class="relative mx-auto border-gray-800 bg-gray-800 border-[8px] rounded-t-xl h-[200px] md:h-[450px] w-full shadow-2xl overflow-hidden">
                <div class="rounded-sm overflow-hidden h-full bg-white">
                    <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?q=80&w=2072&auto=format&fit=crop" 
                         class="w-full h-full object-cover" alt="Sistem Sarpras">
                </div>
            </div>
            <div class="relative mx-auto bg-gray-900 rounded-b-xl rounded-t-sm h-[15px] md:h-[25px] w-[105%] -left-[2.5%] shadow-xl"></div>
            <div class="relative mx-auto bg-gray-700 rounded-b-xl h-[5px] md:h-[10px] w-[20%]"></div>
        </div>

        <div class="mt-20">
            <a href="{{ route('login') }}" class="px-10 py-5 bg-indigo-600 text-white rounded-[2rem] font-bold shadow-2xl shadow-indigo-200 hover:bg-indigo-700 hover:px-12 transition-all duration-300">
                Mulai Pinjam Sekarang &rarr;
            </a>
        </div>
    </main>

    <footer class="py-10 text-center text-slate-400 text-[10px] font-bold uppercase tracking-[0.3em]">
        © 2026 Jurusan PPLG — SMKN 1 Ciomas
    </footer>

</body>
</html>