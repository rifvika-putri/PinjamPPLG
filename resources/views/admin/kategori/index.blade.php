<x-app-layout>
    <div x-data="{ openModal: false }" class="p-6 space-y-6">
        
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Manajemen Kategori</h2>
                <p class="text-xs text-gray-400">Kelola kategori untuk katalog barang sekolah</p>
            </div>
            <button @click="openModal = true" 
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-2xl text-xs font-bold shadow-lg shadow-indigo-100 flex items-center transition-all">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> TAMBAH KATEGORI
            </button>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-[10px] uppercase font-black tracking-widest text-gray-400">
                    <tr>
                        <th class="px-6 py-4">Nama Kategori</th>
                        <th class="px-6 py-4">Slug</th>
                        <th class="px-6 py-4">Jumlah Barang</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($kategoris as $k)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 font-bold text-gray-700">{{ $k->nama_kategori }}</td>
                        <td class="px-6 py-4 font-mono text-[10px] text-indigo-400">{{ $k->slug }}</td>
                        <td class="px-6 py-4 text-xs text-gray-500">
                            {{ $k->barangs_count }} Barang
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('kategori.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini? Semua barang di dalamnya akan ikut terhapus.')">
                                @csrf @method('DELETE')
                                <button class="text-red-400 hover:text-red-600 p-2 rounded-lg hover:bg-red-50 transition">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic text-sm">Belum ada kategori. Silakan tambah dulu.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div x-show="openModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" style="display: none;" x-transition>
            <div class="bg-white rounded-3xl w-full max-w-sm overflow-hidden shadow-2xl" @click.away="openModal = false">
                <div class="bg-indigo-600 p-6 text-white text-center">
                    <h3 class="font-bold text-lg">Tambah Kategori Baru</h3>
                    <p class="text-[10px] opacity-70">Gunakan nama yang jelas seperti 'Elektronik' atau 'Olahraga'</p>
                </div>

                <form action="{{ route('kategori.store') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-extrabold text-gray-400 uppercase mb-2">Nama Kategori</label>
                        <input type="text" name="nama_kategori" required autofocus
                            class="w-full bg-gray-50 border-gray-100 rounded-2xl text-sm focus:ring-indigo-500 placeholder:text-gray-300"
                            placeholder="Contoh: Alat Tulis">
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="openModal = false" class="flex-1 py-3 bg-gray-100 text-gray-500 rounded-2xl font-bold text-xs">BATAL</button>
                        <button type="submit" class="flex-1 py-3 bg-indigo-600 text-white rounded-2xl font-bold text-xs shadow-lg shadow-indigo-100">SIMPAN</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>