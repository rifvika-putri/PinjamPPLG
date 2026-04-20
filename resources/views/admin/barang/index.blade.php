<x-app-layout>
    <x-slot name="header">Inventaris Sarpras PPLG</x-slot>

    <div x-data="{ 
        search: '', 
        filterKondisi: 'semua', 
        filterStatus: 'semua',
        filterKategori: 'semua',
        modalTambah: false, 
        modalDetail: false, 
        modalEdit: false,
        selectedItem: {}, 
        kondisi: 'baik'
    }" class="space-y-6 relative">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-20">
            <div>
                <h2 class="text-2xl font-black text-indigo-900 tracking-tight">Katalog Barang</h2>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Manajemen Inventaris Sisapras</p>
            </div>
        
            <div class="flex items-center gap-3">
                <div class="relative">
                    <input x-model="search" type="text" placeholder="Cari barang..." 
                        class="bg-white border-none rounded-2xl text-xs w-64 shadow-sm py-3 px-5 focus:ring-2 focus:ring-indigo-500 transition-all">
                    <i class="fa-solid fa-magnifying-glass absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                </div>
                <button @click="modalTambah = true" 
                    type="button"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-7 py-3 rounded-2xl text-xs font-bold shadow-lg shadow-indigo-100 transition-all whitespace-nowrap active:scale-95 cursor-pointer">
                    + Tambah Unit
                </button>
            </div>
        </div>

        <div class="flex flex-wrap items-end gap-4 p-6 bg-white rounded-[2rem] border border-gray-100/50 shadow-sm relative z-10">
            {{-- Filter Kondisi --}}
            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black text-gray-400 uppercase ml-2 tracking-widest">Kondisi Aset</label>
                <div class="relative">
                    <select x-model="filterKondisi" 
                        class="appearance-none bg-gray-50 border-none text-gray-700 rounded-2xl text-[11px] font-black uppercase tracking-wider pl-5 pr-10 py-3 shadow-sm focus:ring-2 focus:ring-indigo-500 cursor-pointer w-48">
                        <option value="semua">🌐 Semua Kondisi</option>
                        <option value="Baik">🟢 Kondisi Baik</option>
                        <option value="Rusak">🔴 Rusak</option>
                        <option value="Perbaikan">🛠️ Perbaikan</option>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                        <i class="fa-solid fa-chevron-down text-[10px]"></i>
                    </div>
                </div>
            </div>

            {{-- Filter Status --}}
            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black text-gray-400 uppercase ml-2 tracking-widest">Status Peminjaman</label>
                <div class="relative">
                    <select x-model="filterStatus" 
                        class="appearance-none bg-gray-50 border-none text-gray-700 rounded-2xl text-[11px] font-black uppercase tracking-wider pl-5 pr-10 py-3 shadow-sm focus:ring-2 focus:ring-indigo-500 cursor-pointer w-48">
                        <option value="semua">🌐 Semua Status</option>
                        <option value="Tersedia">📦 Tersedia</option>
                        <option value="Dipinjam">🤝 Dipinjam</option>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                        <i class="fa-solid fa-chevron-down text-[10px]"></i>
                    </div>
                </div>
            </div>

            {{-- Filter Kategori --}}
            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black text-gray-400 uppercase ml-2 tracking-widest">Kategori Aset</label>
                <div class="relative">
                    <select x-model="filterKategori" 
                        class="appearance-none bg-gray-50 border-none text-gray-700 rounded-2xl text-[11px] font-black uppercase tracking-wider pl-5 pr-10 py-3 shadow-sm focus:ring-2 focus:ring-indigo-500 cursor-pointer w-48">
                        <option value="semua">📂 Semua Kategori</option>
                        
                        {{-- Loop data kategori dari database --}}
                        @foreach($kategoris as $k)
                            <option value="{{ $k->nama_kategori }}">📦 {{ $k->nama_kategori }}</option>
                        @endforeach
                        <tr x-cloak x-show="document.querySelectorAll('tbody tr[style*=\'display: none\']').length === {{ count($barangs) }}">
                        <td colspan="10" class="py-20 text-center">
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <span class="text-4xl">🔎</span>
                                <p class="text-gray-400 font-medium text-sm">Yah, barang dengan filter ini tidak ditemukan...</p>
                            </div>
                        </td>
                    </tr>
                    </select>
                    
                    {{-- Icon Arrow --}}
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                        <i class="fa-solid fa-chevron-down text-[10px]"></i>
                    </div>
                </div>
            </div>

            {{-- Tombol Reset --}}
            <div class="flex flex-col gap-2 justify-end">
                <button type="button" @click="filterKondisi = 'semua'; filterStatus = 'semua'; filterKategori = 'semua'; search = ''" 
                    class="p-3 bg-slate-100 text-gray-500 rounded-2xl hover:bg-rose-500 hover:text-white transition-all shadow-sm group cursor-pointer"
                    title="Reset Filter">
                    <i class="fa-solid fa-rotate-right text-xs group-hover:rotate-180 transition-transform duration-500"></i>
                </button>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-50 overflow-hidden relative z-0">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50 border-b border-gray-50">
                    <tr class="text-[10px] uppercase font-black tracking-[0.15em] text-gray-400">
                        <th class="px-10 py-6">Identitas</th>
                        <th class="px-6 py-6 text-center">Status</th>
                        <th class="px-6 py-6">Kondisi</th>
                        <th class="px-10 py-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($barangs as $b)
                        <tr x-show="
                        (
                            {{-- Logika Search: Cek Nama atau Kode --}}
                            search === '' || 
                            '{{ strtolower($b->nama_barang) }}'.includes(search.toLowerCase()) || 
                            '{{ strtolower($b->kode_barang) }}'.includes(search.toLowerCase())
                        ) && 
                        (
                            {{-- Logika Kondisi --}}
                            filterKondisi === 'semua' || 
                            filterKondisi === '{{ $b->kondisi }}'
                        ) && 
                        (
                            {{-- Logika Kategori (Pastikan variabel filterKategori ada di x-data) --}}
                            filterKategori === 'semua' || 
                            filterKategori === '{{ $b->kategori->nama_kategori }}'
                        ) && 
                        (
                            {{-- Logika Status --}}
                            filterStatus === 'semua' || 
                            filterStatus === '{{ $b->status }}'
                        )
                    "
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    class="hover:bg-indigo-50/30 transition-all group">
                            
                            <td class="px-10 py-5">
                                <div class="flex items-center gap-5">
                                    <img src="{{ asset('uploads/barang/' . $b->foto) }}" class="w-14 h-14 rounded-[1.2rem] object-cover border-2 border-white shadow-sm group-hover:scale-105 transition-transform">
                                    <div>
                                        <div class="text-sm font-bold text-gray-800">{{ $b->nama_barang }}</div>
                                        <div class="text-[10px] font-black text-indigo-500 tracking-wider uppercase">{{ $b->kode_barang }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-5 text-center text-[10px] font-black uppercase">
                                <span class="{{ $b->status == 'tersedia' ? 'text-emerald-500 bg-emerald-50' : 'text-amber-500 bg-amber-50' }} px-3 py-1 rounded-lg">
                                    {{ $b->status }}
                                </span>
                            </td>
                            
                            <td class="px-6 py-5">
                                <span class="text-[11px] font-bold {{ $b->kondisi == 'Baik' ? 'text-blue-500' : 'text-rose-500' }}">
                                    {{ $b->kondisi == 'Baik' ? '🟢 Baik' : ($b->kondisi == 'Rusak' ? '🔴 Rusak' : '🛠️ Perbaikan') }}
                                </span>
                            </td>
                            
                            <td class="px-10 py-5">
                                <div class="flex justify-end gap-3">
                                    <button type="button" @click="selectedItem = {{ $b->toJson() }}; modalDetail = true"
                                        class="w-10 h-10 flex items-center justify-center bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-800 hover:text-white transition-all shadow-sm cursor-pointer">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </button>
                                    
                                    <button type="button" @click="selectedItem = {{ json_encode($b) }}; modalEdit = true; kondisi = selectedItem.kondisi" 
                                        class="w-10 h-10 flex items-center justify-center bg-amber-100 text-amber-600 rounded-xl hover:bg-amber-500 hover:text-white transition-all shadow-sm cursor-pointer">
                                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                                    </button>
                                    
                                    <form action="{{ route('admin.barang.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Yakin hapus barang ini?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                            class="w-10 h-10 flex items-center justify-center bg-rose-100 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition-all shadow-sm cursor-pointer">
                                            <i class="fa-solid fa-trash-can text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- MODAL TAMBAH BARANG (ALIGNED WITH EDIT FORM STYLE) --}}
        <div x-show="modalTambah" 
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 md:p-6 bg-slate-900/60 backdrop-blur-md" 
            x-cloak 
            x-transition
            x-data="{ kondisi: 'baik' }">
            
            {{-- Container Utama --}}
            <div @click.away="modalTambah = false" 
                class="bg-white w-full max-w-2xl max-h-[90vh] rounded-[3rem] shadow-2xl overflow-hidden border border-white/20 flex flex-col">
                
                {{-- Sticky Header --}}
                <div class="px-10 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50 shrink-0">
                    <div>
                        <h3 class="text-2xl font-black text-gray-800 tracking-tight">Tambah Unit Barang</h3>
                        <p class="text-xs text-gray-400 font-medium mt-1">Lengkapi data untuk menambahkan inventaris baru</p>
                    </div>
                    <button @click="modalTambah = false" class="text-gray-400 hover:text-gray-900 text-3xl font-light">&times;</button>
                </div>

                {{-- Scrollable Content Area --}}
                <div class="flex-1 overflow-y-auto custom-scrollbar p-10 space-y-6">
                    <form id="formTambahBarang" action="{{ route('admin.barang.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        {{-- Bagian 1: Identitas & Deskripsi --}}
                        <div class="grid grid-cols-2 gap-5">
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase ml-4 tracking-widest">Kode Barang</label>
                                <input type="text" name="kode_barang" placeholder="Contoh: LAP-001" class="bg-gray-50 border-none rounded-[1.5rem] text-sm p-4 w-full focus:ring-2 focus:ring-indigo-500 font-semibold" required>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase ml-4 tracking-widest">Nama Barang</label>
                                <input type="text" name="nama_barang" placeholder="Nama Lengkap Barang" class="bg-gray-50 border-none rounded-[1.5rem] text-sm p-4 w-full focus:ring-2 focus:ring-indigo-500 font-semibold" required>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase ml-4 tracking-widest">Deskripsi Barang</label>
                            <textarea name="deskripsi" placeholder="Jelaskan spesifikasi singkat barang..." class="bg-gray-50 border-none rounded-[1.5rem] text-sm p-4 w-full focus:ring-2 focus:ring-indigo-500 font-semibold" rows="3"></textarea>
                        </div>

                        {{-- Bagian 2: Kategori & Lokasi --}}
                        <div class="grid grid-cols-2 gap-5">
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase ml-4 tracking-widest">Kategori</label>
                                <select name="kategori_id" class="bg-gray-50 border-none rounded-[1.5rem] text-sm p-4 w-full font-semibold focus:ring-2 focus:ring-indigo-500">
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    @foreach($kategoris as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase ml-4 tracking-widest">Lokasi Simpan</label>
                                <input type="text" name="lokasi" placeholder="Nama Ruangan/Rak" class="bg-gray-50 border-none rounded-[1.5rem] text-sm p-4 w-full focus:ring-2 focus:ring-indigo-500 font-semibold" required>
                            </div>
                        </div>

                        <hr class="border-gray-100">

                        {{-- Bagian 3: Kondisi (Sesuai Sistem Edit) --}}
                        <div class="space-y-4">
                            <label class="block text-[10px] font-black text-gray-400 uppercase ml-4 tracking-widest">Status Fisik Barang</label>
                            <div class="grid grid-cols-3 gap-3">
                                <label :class="kondisi === 'baik' ? 'ring-2 ring-emerald-500 bg-emerald-50' : 'bg-gray-50'" class="flex flex-col items-center justify-center p-4 rounded-2xl cursor-pointer transition-all border border-transparent">
                                    <input type="radio" x-model="kondisi" name="kondisi" value="baik" class="hidden">
                                    <span class="text-lg mb-1">🟢</span>
                                    <span class="text-[10px] font-black uppercase tracking-tighter" :class="kondisi === 'baik' ? 'text-emerald-700' : 'text-gray-500'">Kondisi Baik</span>
                                </label>
                                <label :class="kondisi === 'rusak' ? 'ring-2 ring-rose-500 bg-rose-50' : 'bg-gray-50'" class="flex flex-col items-center justify-center p-4 rounded-2xl cursor-pointer transition-all border border-transparent">
                                    <input type="radio" x-model="kondisi" name="kondisi" value="rusak" class="hidden">
                                    <span class="text-lg mb-1">🔴</span>
                                    <span class="text-[10px] font-black uppercase tracking-tighter" :class="kondisi === 'rusak' ? 'text-rose-700' : 'text-gray-500'">Kondisi Rusak</span>
                                </label>
                                <label :class="kondisi === 'perbaikan' ? 'ring-2 ring-amber-500 bg-amber-50' : 'bg-gray-50'" class="flex flex-col items-center justify-center p-4 rounded-2xl cursor-pointer transition-all border border-transparent">
                                    <input type="radio" x-model="kondisi" name="kondisi" value="perbaikan" class="hidden">
                                    <span class="text-lg mb-1">🛠️</span>
                                    <span class="text-[10px] font-black uppercase tracking-tighter" :class="kondisi === 'perbaikan' ? 'text-amber-700' : 'text-gray-500'">Dalam Perbaikan</span>
                                </label>
                            </div>
                        </div>

                        {{-- Form Tambahan Jika Rusak --}}
                        <div x-show="kondisi === 'rusak'" x-transition class="space-y-4 bg-rose-50/50 p-6 rounded-[2rem] border border-rose-100">
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-rose-400 uppercase ml-2 tracking-widest">Tingkat Kerusakan</label>
                                <select name="level_kerusakan" :required="kondisi === 'rusak'" class="bg-white border-none rounded-xl text-sm p-3 w-full font-semibold focus:ring-2 focus:ring-rose-500">
                                    <option value="ringan">Ringan</option>
                                    <option value="sedang">Sedang</option>
                                    <option value="berat">Berat</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-rose-400 uppercase ml-2 tracking-widest">Catatan Kerusakan</label>
                                <textarea name="catatan_kerusakan" :required="kondisi === 'rusak'" placeholder="Jelaskan detail kerusakan..." class="bg-white border-none rounded-xl text-sm p-4 w-full focus:ring-2 focus:ring-rose-500 font-semibold" rows="3"></textarea>
                            </div>
                        </div>

                        {{-- Upload Foto --}}
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase ml-4 tracking-widest">Foto Barang</label>
                            <div class="bg-gray-50 p-6 rounded-[2rem] border-2 border-dashed border-gray-200 text-center">
                                <input type="file" name="foto" class="text-xs text-gray-500 file:mr-4 file:py-2 file:px-6 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer">
                                <p class="text-[9px] text-gray-400 mt-2 italic">*Kosongkan jika tidak ada foto</p>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Sticky Footer --}}
                <div class="p-8 bg-white border-t border-gray-50 shrink-0">
                    <button type="submit" form="formTambahBarang" class="w-full py-5 bg-indigo-600 text-white rounded-[2rem] font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95">
                        Simpan Inventaris Baru
                    </button>
                </div>
            </div>
        </div>

        {{-- MODAL DETAIL --}}
        <div x-show="modalDetail" 
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 md:p-6 bg-slate-900/60 backdrop-blur-md" 
            x-cloak 
            x-transition>
            
            <div @click.away="modalDetail = false" 
                class="bg-white w-full max-w-2xl max-h-[90vh] rounded-[3rem] shadow-2xl overflow-hidden border border-white/20 flex flex-col">
                
                <div class="px-10 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30 shrink-0">
                    <h3 class="text-2xl font-black text-gray-800 tracking-tight">Detail Inventaris</h3>
                    <button @click="modalDetail = false" class="text-gray-400 hover:text-gray-900 text-3xl font-light transition-colors">&times;</button>
                </div>

                <div class="flex-1 overflow-y-auto custom-scrollbar p-10 space-y-8">
                    
                    <div class="flex flex-col md:flex-row gap-8">
                        <div class="w-full md:w-1/2">
                            <img :src="selectedItem.foto ? `/PinjamPPLG/public/uploads/barang/${selectedItem.foto}` : '/PinjamPPLG/public/images/placeholder.png'"
                                class="w-full h-64 object-cover rounded-[2.5rem] shadow-lg border-4 border-white shadow-indigo-100/50">
                        </div>
                        
                        <div class="w-full md:w-1/2 space-y-5 flex flex-col justify-center">
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Informasi Aset</p>
                                <h4 class="text-3xl font-black text-gray-900 leading-tight mb-1" x-text="selectedItem.nama_barang"></h4>
                                <div class="inline-flex items-center px-3 py-1 bg-slate-100 rounded-lg border border-slate-200">
                                    <span class="text-xs font-bold text-indigo-600 font-mono tracking-wider" x-text="selectedItem.kode_barang"></span>
                                </div>
                            </div>

                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Kategori</p>
                                <span class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-2xl text-[11px] font-black uppercase tracking-tighter border border-indigo-100/50" 
                                    x-text="selectedItem.kategori ? selectedItem.kategori.nama_kategori : 'Tanpa Kategori'">
                                </span>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-50">

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="bg-slate-50/80 p-5 rounded-[2rem] border border-gray-100">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Status Unit</p>
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full animate-pulse" :class="selectedItem.status === 'tersedia' ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]' : 'bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.5)]'"></span>
                                <p class="text-xs font-black text-gray-700 uppercase" x-text="selectedItem.status"></p>
                            </div>
                        </div>
                        <div class="bg-slate-50/80 p-5 rounded-[2rem] border border-gray-100">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Kondisi Fisik</p>
                            <p class="text-xs font-black text-gray-700 uppercase" 
                                x-text="selectedItem.kondisi === 'Baik' ? '🟢 Baik' : (selectedItem.kondisi === 'Rusak' ? '🔴 Rusak' : '🛠️ Perbaikan')">
                            </p>
                        </div>
                        <div class="bg-slate-50/80 p-5 rounded-[2rem] border border-gray-100 col-span-2 md:col-span-1">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Lokasi Rak</p>
                            <p class="text-xs font-black text-gray-700 uppercase" x-text="selectedItem.lokasi"></p>
                        </div>
                    </div>

                    <div class="px-2">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-3">Deskripsi Aset</p>
                        <div class="p-5 bg-gray-50/50 rounded-3xl border border-dashed border-gray-200">
                            <p class="text-sm text-gray-600 leading-relaxed font-medium" x-text="selectedItem.deskripsi || 'Tidak ada deskripsi tambahan.'"></p>
                        </div>
                    </div>

                    <template x-if="selectedItem.kondisi === 'Rusak'">
                        <div class="bg-rose-50/50 rounded-[2.5rem] p-8 border border-rose-100 space-y-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-rose-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-rose-200">
                                    <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                                </div>
                                <div>
                                    <h5 class="text-sm font-black text-rose-700 uppercase tracking-tight">Laporan Kerusakan</h5>
                                    <p class="text-[10px] text-rose-400 font-bold">Data ditemukan pada unit ini</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                                <div class="bg-white/60 p-4 rounded-2xl">
                                    <p class="text-[9px] font-black text-rose-400 uppercase tracking-widest mb-1">Tingkat</p>
                                    <p class="text-sm font-bold text-rose-600 capitalize" x-text="selectedItem.level_kerusakan"></p>
                                </div>
                                <div class="bg-white/60 p-4 rounded-2xl">
                                    <p class="text-[9px] font-black text-rose-400 uppercase tracking-widest mb-1">Catatan Teknisi</p>
                                    <p class="text-sm font-medium text-rose-600/80" x-text="selectedItem.catatan_kerusakan || '-'"></p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="p-8 bg-gray-50/50 border-t border-gray-50 flex justify-end shrink-0">
                    <button @click="modalDetail = false" 
                        class="px-10 py-4 bg-white border border-gray-200 text-gray-500 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all duration-300 shadow-sm">
                        Tutup Pratinjau
                    </button>
                </div>
            </div>
        </div>

        {{-- MODAL EDIT --}}
        <div x-show="modalEdit" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-md" x-cloak x-transition>
            <div @click.away="modalEdit = false" class="bg-white w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-[3rem] shadow-2xl border border-white/20 custom-scrollbar">
                <div class="px-10 py-8 border-b border-gray-50 flex justify-between items-center sticky top-0 bg-white z-10">
                    <h3 class="text-2xl font-black text-gray-800 tracking-tight">Perbarui Informasi</h3>
                    <button @click="modalEdit = false" class="text-gray-400 text-3xl font-light">&times;</button>
                </div>

                <form :action="window.location.origin + window.location.pathname + '/' + selectedItem.id" 
                method="POST" 
                enctype="multipart/form-data" 
                class="p-10 space-y-6">
                @csrf 
                @method('PUT')

                    <div class="grid grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Kode Barang</label>
                            <input type="text" name="kode_barang" x-model="selectedItem.kode_barang" class="bg-gray-50 border-none rounded-[1.5rem] text-sm p-4 w-full focus:ring-2 focus:ring-indigo-500 font-mono" placeholder="Contoh: LAP-001">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Nama Barang</label>
                            <input type="text" name="nama_barang" x-model="selectedItem.nama_barang" class="bg-gray-50 border-none rounded-[1.5rem] text-sm p-4 w-full focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Kategori</label>
                            <select name="kategori_id" x-model="selectedItem.kategori_id" class="bg-gray-50 border-none rounded-[1.5rem] text-sm p-4 w-full focus:ring-2 focus:ring-indigo-500">
                                @foreach($kategoris as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Lokasi</label>
                            <input type="text" name="lokasi" x-model="selectedItem.lokasi" class="bg-gray-50 border-none rounded-[1.5rem] text-sm p-4 w-full focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Ganti Foto Barang <span class="text-gray-300 font-medium">(Opsional)</span></label>
                        <div class="relative group">
                            <input type="file" name="foto" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-[1.5rem] p-4 flex items-center justify-between group-hover:bg-gray-100 transition-colors">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-image text-gray-400 ml-2"></i>
                                    <span class="text-xs text-gray-500 font-medium">Klik untuk pilih foto baru...</span>
                                </div>
                                <span class="text-[9px] bg-white px-3 py-1 rounded-full border border-gray-100 shadow-sm font-bold text-gray-400 uppercase">Browse</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Deskripsi</label>
                        <textarea name="deskripsi" x-model="selectedItem.deskripsi" rows="3" class="bg-gray-50 border-none rounded-[1.5rem] text-sm p-4 w-full focus:ring-2 focus:ring-indigo-500" placeholder="Tambahkan deskripsi detail barang..."></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Kondisi Barang</label>
                            <select name="kondisi" x-model="selectedItem.kondisi" class="bg-gray-50 border-none rounded-[1.5rem] text-sm p-4 w-full">
                                <option value="Baik">🟢 Kondisi Baik</option>
                                <option value="Rusak">🔴 Rusak</option>
                                <option value="Perbaikan">🛠️ Perbaikan</option>
                            </select>
                        </div>
                        <div class="space-y-2" x-show="selectedItem.kondisi === 'Rusak'">
                            <label class="text-[10px] font-black text-rose-400 uppercase ml-2">Tingkat Kerusakan</label>
                            <select name="level_kerusakan" x-model="selectedItem.level_kerusakan" class="bg-rose-50 border-none rounded-[1.5rem] text-sm p-4 w-full text-rose-600 font-bold">
                                <option value="ringan">Rusak Ringan</option>
                                <option value="sedang">Rusak Sedang</option>
                                <option value="berat">Rusak Berat</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2" x-show="selectedItem.kondisi === 'Rusak'" x-transition>
                        <label class="text-[10px] font-black text-rose-400 uppercase ml-2">Catatan Kerusakan</label>
                        <textarea name="catatan_kerusakan" 
                                x-model="selectedItem.catatan_kerusakan" 
                                rows="2" 
                                class="bg-rose-50 border-none rounded-[1.5rem] text-sm p-4 w-full text-rose-700 placeholder-rose-300 focus:ring-2 focus:ring-rose-500" 
                                placeholder="Contoh: Layar pecah di bagian pojok kanan bawah..."></textarea>
                    </div>

                    <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-[1.8rem] font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 hover:bg-slate-900 transition-all duration-300">Update Database</button>
                </form>
            </div>
        </div>
    </div> 
</x-app-layout>

<style>
    [x-cloak] { display: none !important; }
    /* Bikin scrollbar-nya jadi estetik & tipis */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
</style>