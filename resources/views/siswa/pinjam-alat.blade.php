<x-app-layout>
    <div class="py-12 bg-gray-50" x-data="{ selectedBarang: null, search: '', showForm: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h2 class="text-3xl font-black text-gray-800 tracking-tight">Katalog Alat Praktik</h2>
                    <p class="text-gray-500 text-sm">Pilih alat yang ingin kamu pinjam hari ini.</p>
                </div>

                <div class="relative w-full md:w-96">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                    </div>
                    <input 
                        type="text" 
                        x-model="search" 
                        placeholder="Cari nama alat atau kategori..."
                        class="block w-full pl-12 pr-4 py-4 bg-white border-none rounded-[2rem] shadow-sm focus:ring-2 focus:ring-indigo-500 transition-all text-sm font-medium"
                    >
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                        <span class="bg-indigo-50 text-indigo-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase">
                            {{ $barangs->count() }} Alat
                        </span>
                    </div>
                </div>
            </div>

           <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">
    @forelse($barangs as $item)
        @php
            // Logika pengecekan ketersediaan
            $isDipinjam = $item->status === 'dipinjam';
            $isRusak = $item->level_kerusakan === 'berat' || $item->status === 'rusak';
            $canPinjam = !$isDipinjam && !$isRusak;
        @endphp

        <div 
            x-show="search === '' || '{{ strtolower($item->nama_barang) }}'.includes(search.toLowerCase()) || '{{ strtolower($item->kategori->nama_kategori ?? '') }}'.includes(search.toLowerCase())"
            x-transition:enter="transition ease-out duration-300" 
            x-transition:enter-start="opacity-0 transform scale-95"
            class="group relative bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-500"
        >
            <div class="relative aspect-[4/3] bg-gray-100 overflow-hidden">
                @if($item->foto)
                    <img src="{{ asset('uploads/barang/' . $item->foto) }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 {{ !$canPinjam ? 'grayscale contrast-125 opacity-70' : '' }}">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-300 bg-gray-50">
                        <i data-lucide="package" class="w-12 h-12 mb-2"></i>
                        <span class="text-[10px] italic font-medium tracking-widest uppercase">No Image</span>
                    </div>
                @endif

                @if(!$canPinjam)
                    <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px] flex items-center justify-center p-4">
                        <div class="bg-white/90 px-4 py-2 rounded-2xl shadow-xl transform -rotate-3">
                            <span class="text-[11px] font-black uppercase tracking-widest {{ $isRusak ? 'text-rose-600' : 'text-amber-600' }}">
                                {{ $isRusak ? '⚠️ Rusak Berat' : '⏳ Sedang Dipinjam' }}
                            </span>
                        </div>
                    </div>
                @endif

                <div class="absolute top-4 right-4">
                    <span class="px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-tighter shadow-md {{ $canPinjam ? 'bg-emerald-500 text-white' : 'bg-gray-400 text-white' }}">
                        {{ $canPinjam ? 'Tersedia' : 'Non-Aktif' }}
                    </span>
                </div>
            </div>

            <div class="p-6">
                <div class="flex justify-between items-start mb-1">
                    <p class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.2em]">
                        {{ $item->kategori->nama_kategori ?? 'Umum' }}
                    </p>
                </div>

                <h4 class="font-bold text-gray-800 text-lg leading-tight mb-1 group-hover:text-indigo-600 transition-colors">
                    {{ $item->nama_barang }}
                </h4>
                
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-[11px] text-gray-400 font-mono tracking-wider">{{ $item->kode_barang }}</span>
                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                    <span class="text-[10px] font-bold {{ $item->kondisi == 'Baik' ? 'text-emerald-500' : 'text-rose-500' }}">
                         Kondisi: {{ $item->kondisi }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-3 mt-4 pt-4 border-t border-gray-50">
                    <button 
                        @click="selectedBarang = {{ json_encode($item) }}; showForm = false"
                        class="flex items-center justify-center gap-2 bg-gray-50 hover:bg-gray-100 text-gray-600 py-3 rounded-2xl text-[11px] font-bold transition-all active:scale-95 border border-gray-100"
                    >
                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                        Detail
                    </button>

                    @if($canPinjam)
                        <button 
                            @click="selectedBarang = {{ json_encode($item) }}; showForm = true"
                            class="flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-2xl text-[11px] font-bold shadow-lg shadow-indigo-100 transition-all active:scale-95"
                        >
                            <i data-lucide="plus-circle" class="w-3.5 h-3.5 text-indigo-200"></i>
                            Pinjam
                        </button>
                    @else
                        <button 
                            disabled
                            class="flex items-center justify-center gap-2 bg-gray-100 text-gray-400 py-3 rounded-2xl text-[11px] font-bold cursor-not-allowed opacity-60"
                        >
                            <i data-lucide="lock" class="w-3.5 h-3.5"></i>
                            Lock
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full py-20 text-center bg-white rounded-[3rem] border border-dashed border-gray-200">
            <i data-lucide="search-x" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
            <p class="text-gray-400 font-medium italic">Belum ada alat praktik yang bisa ditampilkan.</p>
        </div>
    @endforelse
</div>

        <template x-if="selectedBarang && !showForm">
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm overflow-y-auto" @click.self="selectedBarang = null">
                <div class="bg-white w-full max-w-2xl rounded-[3rem] overflow-hidden shadow-2xl animate-in fade-in zoom-in duration-300">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-1/2 aspect-square bg-gray-100">
                            <img :src="selectedBarang.foto ? 'uploads/barang/' + selectedBarang.foto : '/placeholder.png'" class="w-full h-full object-cover">
                        </div>
                        <div class="md:w-1/2 p-8 relative">
                            <button @click="selectedBarang = null" class="absolute top-4 right-6 text-gray-400 hover:text-gray-600 transition-colors">
                                <i data-lucide="x" class="w-6 h-6"></i>
                            </button>
                            <h3 class="text-2xl font-bold text-gray-800 leading-tight mb-1" x-text="selectedBarang.nama_barang"></h3>
                            <p class="text-xs font-mono text-gray-400 mb-6 uppercase tracking-widest" x-text="selectedBarang.kode_barang"></p>
                            <div class="space-y-4 text-left">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] uppercase font-bold text-gray-400">Lokasi Penyimpanan</p>
                                        <p class="text-xs font-bold text-gray-700" x-text="selectedBarang.lokasi"></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                                        <i data-lucide="shield-check" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] uppercase font-bold text-gray-400">Kondisi Alat</p>
                                        <p class="text-xs font-bold text-gray-700" x-text="selectedBarang.kondisi"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-8 text-left">
                                <p class="text-[9px] uppercase font-bold text-gray-400 mb-2 tracking-widest underline decoration-indigo-200 underline-offset-4">Deskripsi</p>
                                <p class="text-xs text-gray-600 leading-relaxed italic" x-text="selectedBarang.deskripsi || selectedBarang.catatan || 'Tidak ada deskripsi.'"></p>
                            </div>
                            <div class="mt-10">
                                <button @click="showForm = true" class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-bold text-xs shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95">
                                    Mulai Pinjam Sekarang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template x-if="selectedBarang && showForm">
            <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto" @click.self="showForm = false">
                <div class="bg-white w-full max-w-xl rounded-[2.5rem] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300" x-data="{ pengembalianOpsi: 'hari_ini', setujuSyarat: false }">
                    <div class="bg-indigo-600 p-6 text-white flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                            <h3 class="text-lg font-bold">Form Peminjaman Alat</h3>
                        </div>
                        <button @click="showForm = false" class="hover:bg-white/20 p-2 rounded-full transition-colors">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <form action="{{ route('peminjaman.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                        @csrf
                        <input type="hidden" name="barang_id" :value="selectedBarang.id">
                        <div class="grid grid-cols-3 gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100 text-left">
                            <div>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Nama Siswa</span>
                                <p class="text-xs font-bold text-slate-700 truncate">{{ Auth::user()->name }}</p>
                            </div>
                            <div>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Kelas</span>
                                <p class="text-xs font-bold text-slate-700">{{ Auth::user()->kelas ?? 'XI RPL' }}</p>
                            </div>
                            <div>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">No. Telp</span>
                                <p class="text-xs font-bold text-slate-700 text-truncate">{{ Auth::user()->no_telp ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100 text-left">
                            <div class="w-16 h-16 rounded-xl overflow-hidden bg-white shadow-sm ring-2 ring-white flex-shrink-0">
                                <img :src="selectedBarang.foto ? 'uploads/barang/' + selectedBarang.foto : '/placeholder.png'" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-slate-800 text-sm" x-text="selectedBarang.nama_barang"></h4>
                                <div class="flex flex-wrap gap-2 mt-1">
                                    <span class="text-[9px] font-mono text-indigo-600 bg-white px-2 py-0.5 rounded-md border border-indigo-100" x-text="selectedBarang.kode_barang"></span>
                                    <template x-if="selectedBarang.level_kerusakan">
                                        <span class="text-[9px] font-bold px-2 py-0.5 rounded-full" :class="selectedBarang.level_kerusakan === 'ringan' ? 'bg-green-100 text-green-700' : selectedBarang.level_kerusakan === 'sedang' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700'" x-text="'Kerusakan: ' + selectedBarang.level_kerusakan.toUpperCase()"></span>
                                    </template>
                                    <span class="text-[9px] font-bold text-emerald-600 uppercase" x-text="'Kondisi: ' + selectedBarang.kondisi"></span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2 text-left">
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Tgl Pinjam</label>
                                <input type="text" value="{{ date('d/m/Y') }}" class="w-full rounded-xl border-slate-200 bg-slate-50 text-xs font-bold text-slate-500" readonly>
                            </div>
                            <div class="space-y-2 text-left">
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Batas Kembali</label>
                                <select x-model="pengembalianOpsi" name="opsi_kembali" class="w-full rounded-xl border-slate-200 text-xs font-bold text-slate-700">
                                    <option value="hari_ini">Hari Ini (15:00)</option>
                                    <option value="manual">Ajukan Opsi lain</option>
                                </select>
                            </div>
                        </div>

                        <div x-show="pengembalianOpsi === 'manual'" x-transition class="space-y-2 text-left animate-in slide-in-from-top-2">
                            <label class="text-[10px] font-black text-indigo-600 uppercase ml-2">Pilih Waktu Pengembalian</label>
                            <input type="datetime-local" name="tgl_kembali" class="w-full rounded-xl border-indigo-200 bg-indigo-50/30 text-xs font-bold text-indigo-700">
                        </div>

                        <div class="space-y-2 text-left">
                            <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Keperluan Pinjam</label>
                            <textarea name="keperluan" rows="2" class="w-full rounded-2xl border-slate-200 text-xs focus:ring-indigo-500" placeholder="Jelaskan tujuan peminjaman..." required></textarea>
                        </div>

                        <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100 text-left">
                            <label class="block text-[9px] font-black text-amber-600 uppercase mb-2 italic">Upload Bukti Pengambilan (Foto bersama Guru/Petugas)</label>
                            <input type="file" name="bukti_ambil" class="w-full text-[10px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-white file:text-amber-600 file:font-bold shadow-sm" required>
                        </div>

                        <div class="flex items-start gap-3 px-2">
                            <input type="checkbox" x-model="setujuSyarat" id="syarat" class="mt-1 rounded border-slate-300 text-indigo-600">
                            <label for="syarat" class="text-[10px] text-slate-500 leading-relaxed cursor-pointer">
                                Saya setuju untuk menjaga barang ini dan bertanggung jawab jika terjadi kerusakan.
                            </label>
                        </div>

                        <div class="flex gap-3 pt-2">
                            <button type="button" @click="showForm = false" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 py-4 rounded-2xl font-bold text-xs transition-all">
                                Kembali
                            </button>
                            <button type="submit" :disabled="!setujuSyarat" :class="!setujuSyarat ? 'opacity-50 cursor-not-allowed bg-slate-400' : 'bg-indigo-600 hover:bg-indigo-700 shadow-xl shadow-indigo-100'" class="flex-[2] text-white py-4 rounded-2xl font-black text-xs tracking-widest transition-all">
                                AJUKAN PEMINJAMAN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>