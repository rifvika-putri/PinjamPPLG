<x-app-layout>
    <div x-data="{ 
            openModalTambah: false, 
            openModalEdit: false, 
            editData: {id: '', nama: ''},
            search: '' 
        }" 
        class="py-12 bg-[#f8fafc] min-h-screen">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">Manajemen Kategori</h2>
                    <p class="text-sm text-gray-500 mt-1">Kelola kategori barang untuk mempermudah inventarisir sekolah.</p>
                </div>
                <button @click="openModalTambah = true" 
                    class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-2xl transition-all duration-300 shadow-lg shadow-indigo-200 group">
                    <i data-lucide="plus" class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform"></i>
                    TAMBAH KATEGORI
                </button>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden transition-all">
                
                <div class="p-8 border-b border-gray-50 bg-white/50 backdrop-blur-sm flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="relative w-full sm:w-96">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </span>
                        <input x-model="search" type="text" placeholder="Cari nama kategori..." 
                               class="block w-full pl-12 pr-4 py-3.5 border-none bg-gray-50 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500/20 transition-all placeholder:text-gray-400 font-medium">
                    </div>
                    <div class="flex items-center gap-2 px-4 py-2 bg-indigo-50 rounded-xl">
                        <i data-lucide="layers" class="w-4 h-4 text-indigo-500"></i>
                        <span class="text-xs font-bold text-indigo-600">{{ $kategoris->count() }} Total Kategori</span>
                    </div>
                </div>

                <div class="overflow-x-auto p-6">
                    <table class="w-full text-left border-separate border-spacing-y-3">
                        <thead>
                            <tr class="text-gray-400 uppercase text-[10px] tracking-[0.2em] font-black">
                                <th class="px-8 py-3">Nama Kategori</th>
                                <th class="px-8 py-3 text-center">Slug URL</th>
                                <th class="px-8 py-3 text-center">Jumlah Barang</th>
                                <th class="px-8 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategoris as $k)
                            <tr x-show="search === '' || '{{ strtolower($k->nama_kategori) }}'.includes(search.toLowerCase())"
                                class="group hover:translate-y-[-2px] transition-all duration-300">
                                
                                <td class="px-8 py-5 bg-gray-50/50 group-hover:bg-white group-hover:shadow-md rounded-l-[2rem] border-y border-l border-transparent group-hover:border-gray-100 transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-8 bg-indigo-500 rounded-full opacity-0 group-hover:opacity-100 transition-all"></div>
                                        <span class="font-bold text-gray-800 text-base">{{ $k->nama_kategori }}</span>
                                    </div>
                                </td>

                                <td class="px-8 py-5 bg-gray-50/50 group-hover:bg-white group-hover:shadow-md border-y border-transparent group-hover:border-gray-100 transition-all text-center">
                                    <span class="font-mono text-[11px] bg-white px-3 py-1.5 rounded-lg border border-gray-100 text-indigo-500 shadow-sm">
                                        /{{ $k->slug }}
                                    </span>
                                </td>

                                <td class="px-8 py-5 bg-gray-50/50 group-hover:bg-white group-hover:shadow-md border-y border-transparent group-hover:border-gray-100 transition-all text-center">
                                    <span class="px-4 py-1.5 rounded-full text-[10px] font-black {{ $k->barangs_count > 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-400' }}">
                                        {{ $k->barangs_count ?? 0 }} ITEMS
                                    </span>
                                </td>

                                <td class="px-8 py-5 bg-gray-50/50 group-hover:bg-white group-hover:shadow-md rounded-r-[2rem] border-y border-r border-transparent group-hover:border-gray-100 transition-all text-right">
                                    <div class="flex justify-end gap-3">
                                        <button @click="editData = {id: '{{ $k->id }}', nama: '{{ $k->nama_kategori }}'}; openModalEdit = true" 
                                                class="p-2.5 text-amber-500 hover:bg-amber-50 rounded-xl transition-all hover:scale-110">
                                            <i data-lucide="pencil-line" class="w-4 h-4"></i>
                                        </button>

                                        <form action="{{ route('kategori.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')" class="inline">
                                            @csrf @method('DELETE')
                                            <button class="p-2.5 text-red-400 hover:bg-red-50 rounded-xl transition-all hover:scale-110">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center">
                                    <p class="text-gray-400 font-medium italic">Belum ada kategori ditemukan.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div x-show="openModalTambah" 
             class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" 
             style="display: none;" x-cloak>
            
            <div class="bg-white rounded-[2.5rem] w-full max-w-sm overflow-hidden shadow-2xl" @click.away="openModalTambah = false">
                <div class="bg-indigo-600 p-8 text-white">
                    <h3 class="font-black text-xl tracking-tight">Kategori Baru</h3>
                    <p class="text-[11px] opacity-70 mt-1 uppercase tracking-widest font-bold">Tambah ke Inventaris</p>
                </div>

                <form action="{{ route('kategori.store') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-3 tracking-widest">Nama Kategori</label>
                        <input type="text" name="nama_kategori" required placeholder="Misal: Elektronik"
                            class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 text-sm focus:ring-2 focus:ring-indigo-500/20 font-bold text-gray-700 transition-all">
                    </div>

                    <div class="flex flex-col gap-3">
                        <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-xs shadow-lg hover:bg-indigo-700 transition-all uppercase tracking-widest">
                            Simpan Kategori
                        </button>
                        <button type="button" @click="openModalTambah = false" class="w-full py-4 bg-gray-50 text-gray-400 rounded-2xl font-bold text-xs hover:bg-gray-100 transition-all uppercase tracking-widest">
                            Batalkan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="openModalEdit" 
             class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" 
             style="display: none;" x-cloak>
            
            <div class="bg-white rounded-[2.5rem] w-full max-w-sm overflow-hidden shadow-2xl" @click.away="openModalEdit = false">
                <div class="bg-amber-500 p-8 text-white">
                    <h3 class="font-black text-xl tracking-tight text-white">Edit Kategori</h3>
                    <p class="text-[11px] opacity-70 mt-1 uppercase tracking-widest font-bold text-white">Ubah Nama Kategori</p>
                </div>

                <form :action="'{{ url('admin/kategori') }}/' + editData.id" method="POST" class="p-8 space-y-6">
                    @csrf 
                    @method('PUT')
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-3 tracking-widest">Nama Kategori</label>
                        <input type="text" name="nama_kategori" x-model="editData.nama" required
                            class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 text-sm focus:ring-2 focus:ring-amber-500/20 font-bold text-gray-700 transition-all">
                    </div>

                    <div class="flex flex-col gap-3">
                        <button type="submit" class="w-full py-4 bg-amber-500 text-white rounded-2xl font-black text-xs shadow-lg hover:bg-amber-600 transition-all uppercase tracking-widest">
                            Update Perubahan
                        </button>
                        <button type="button" @click="openModalEdit = false" class="w-full py-4 bg-gray-50 text-gray-400 rounded-2xl font-bold text-xs hover:bg-gray-100 transition-all uppercase tracking-widest">
                            Batalkan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>