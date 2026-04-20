<x-app-layout>
    <x-slot name="header">Ringkasan Statistik</x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        
        <div class="bg-white p-6 rounded-[2rem] border border-blue-50 shadow-xl shadow-blue-500/10 hover:shadow-blue-500/20 transition-all duration-500 hover:-translate-y-2 group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Barang</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-2">{{ $jumlahBarang }}</h3>
                </div>
                <div class="p-4 rounded-2xl bg-blue-50 text-blue-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="package" class="w-7 h-7"></i>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-50">
                <a href="{{ route('admin.barang.index') }}" class="text-blue-600 text-[10px] font-black uppercase flex items-center">
                    LIHAT BARANG <i data-lucide="chevron-right" class="w-3 h-3 ml-1"></i>
                </a>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-indigo-50 shadow-xl shadow-indigo-500/10 hover:shadow-indigo-500/20 transition-all duration-500 hover:-translate-y-2 group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Petugas</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-2">{{ $jumlahPetugas }}</h3>
                </div>
                <div class="p-4 rounded-2xl bg-indigo-50 text-indigo-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="user-cog" class="w-7 h-7"></i>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-50">
                <a href="{{ route('petugas.index') }}" class="text-indigo-600 text-[10px] font-black uppercase flex items-center">
                    LIHAT PETUGAS <i data-lucide="chevron-right" class="w-3 h-3 ml-1"></i>
                </a>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-emerald-50 shadow-xl shadow-emerald-500/10 hover:shadow-emerald-500/20 transition-all duration-500 hover:-translate-y-2 group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Peminjaman</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-2">{{ $jumlahPeminjaman ?? 0 }}</h3>
                </div>
                <div class="p-4 rounded-2xl bg-emerald-50 text-emerald-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="refresh-cw" class="w-7 h-7"></i>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-50">
                <a href="{{ route('peminjaman.index') }}" class="text-emerald-600 text-[10px] font-black uppercase flex items-center">
                    LIHAT PEMINJAMAN <i data-lucide="chevron-right" class="w-3 h-3 ml-1"></i>
                </a>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-purple-50 shadow-xl shadow-purple-500/10 hover:shadow-purple-500/20 transition-all duration-500 hover:-translate-y-2 group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Siswa</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-2">{{ $jumlahSiswa }}</h3>
                </div>
                <div class="p-4 rounded-2xl bg-purple-50 text-purple-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="graduation-cap" class="w-7 h-7"></i>
                </div>
            </div>
        </div>

    </div>

    <div class="space-y-6">
        @forelse($aktivitasTerakhir as $log)
            <div class="flex items-start gap-4 relative group">
                <div class="flex flex-col items-center">
                    <div class="p-3 rounded-2xl bg-{{ $log->warna }}-50 text-{{ $log->warna }}-600 shadow-sm">
                        <i data-lucide="{{ $log->icon }}" class="w-5 h-5"></i>
                    </div>
                </div>
                
                <div class="flex-1 border-b border-gray-50 pb-4">
                    <div class="flex justify-between items-start">
                        <p class="text-sm font-bold text-slate-700 leading-tight">{{ $log->pesan }}</p>
                        <span class="text-[10px] font-medium text-slate-400 bg-slate-50 px-2 py-1 rounded-md">
                            {{ $log->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest font-black">
                        Oleh: {{ $log->user_name }}
                    </p>
                </div>
            </div>
        @empty
            <div class="text-center py-10 bg-slate-50/50 rounded-3xl border border-dashed border-slate-200">
                <p class="text-slate-500 font-bold italic">Belum ada pergerakan hari ini.</p>
            </div>
        @endforelse
    </div>
</x-app-layout>