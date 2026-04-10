<x-app-layout>
    <x-slot name="header">Inventaris Barang PPLG</x-slot>

    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-[#272b34]">Manajemen Inventaris</h2>
                <p class="text-gray-400 text-sm">Data lengkap sarana prasarana sekolah</p>
            </div>
            
            <div class="flex items-center gap-3">
                <form action="{{ route('barang.index') }}" method="GET" class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari kode/nama..." 
                           class="pl-10 pr-4 py-2 bg-white border-gray-200 rounded-xl text-sm focus:ring-blue-500 w-64 border">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-3"></i>
                </form>
                <button onclick="openModal('modalTambah')" class="bg-[#2f77dd] text-white px-5 py-2.5 rounded-xl font-bold flex items-center text-sm shadow-lg shadow-blue-500/20">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Barang
                </button>
            </div>
        </div>

        @if($isSearching)
            <div class="flex items-center justify-between bg-blue-50 p-4 rounded-2xl border border-blue-100">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-500 p-2 rounded-lg text-white">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <p class="text-blue-700 font-bold text-sm">Hasil pencarian untuk: "{{ request('search') }}"</p>
                        <p class="text-blue-500 text-xs">Ditemukan {{ $barangTersedia->count() }} data terkait</p>
                    </div>
                </div>
                <a href="{{ route('barang.index') }}" class="text-xs font-bold text-blue-600 bg-white px-4 py-2 rounded-xl shadow-sm border border-blue-100 hover:bg-blue-100 transition">
                    Reset Pencarian
                </a>
            </div>
        @else
            <div class="flex space-x-2 bg-gray-100 p-1 rounded-2xl w-fit">
                <button onclick="switchTab('tersedia')" id="btn-tersedia" class="tab-btn px-6 py-2 rounded-xl font-bold text-sm transition bg-white shadow-sm text-blue-600">Tersedia ({{ $barangTersedia->count() }})</button>
                <button onclick="switchTab('dipinjam')" id="btn-dipinjam" class="tab-btn px-6 py-2 rounded-xl font-bold text-sm transition text-gray-500">Dipinjam ({{ $barangDipinjam->count() }})</button>
                <button onclick="switchTab('rusak')" id="btn-rusak" class="tab-btn px-6 py-2 rounded-xl font-bold text-sm transition text-gray-500">Rusak ({{ $barangRusak->count() }})</button>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            @php 
                // Jika sedang mencari, kita hanya pakai key 'tersedia' karena datanya dipusatkan di sana oleh Controller
                $loopData = $isSearching ? ['hasil' => $barangTersedia] : ['tersedia' => $barangTersedia, 'dipinjam' => $barangDipinjam, 'rusak' => $barangRusak];
            @endphp

            @foreach($loopData as $key => $items)
            <div id="tab-{{ $key }}" class="tab-content p-4 overflow-x-auto {{ (!$isSearching && $key != 'tersedia') ? 'hidden' : '' }}">
                <table class="w-full text-left border-separate border-spacing-y-2">
                    <thead class="text-[#272b34] uppercase text-[10px] tracking-widest font-extrabold">
                        <tr>
                            <th class="px-4 py-3 text-center">Foto</th>
                            <th class="px-4 py-3">Kode</th>
                            <th class="px-4 py-3">Nama Barang</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Lokasi</th>
                            <th class="px-4 py-3 text-center">Kondisi</th>
                            <th class="px-4 py-3">Deskripsi</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $b)
                        <tr class="bg-gray-50 hover:bg-white hover:shadow-md transition rounded-2xl">
                            <td class="px-4 py-4 text-center">
                                @if($b->foto)
                                    <button onclick="showPreview('{{ asset('uploads/barang/' . $b->foto) }}')" class="bg-blue-50 text-blue-600 p-2 rounded-lg hover:bg-blue-100 transition">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                @else
                                    <span class="text-[10px] text-gray-400 font-bold uppercase">No Img</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 font-mono text-[10px] text-blue-600 font-bold">{{ $b->kode_barang }}</td>
                            <td class="px-4 py-4 font-bold text-[#272b34]">{{ $b->nama_barang }}</td>
                            <td class="px-4 py-4 text-gray-500 text-sm">{{ $b->kategori->nama_kategori ?? 'Tanpa Kategori' }}</td>
                            <td class="px-4 py-4 text-gray-500 text-sm">{{ $b->lokasi }}</td>
                            <td class="px-4 py-4 text-center">
                                <span class="px-2 py-1 rounded-md text-[10px] font-bold 
                                    {{ $b->kondisi == 'Baik' ? 'bg-green-100 text-green-600' : ($b->kondisi == 'Rusak' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-600') }}">
                                    {{ $b->kondisi }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-gray-400 text-xs italic">{{ Str::limit($b->deskripsi, 20) ?? '-' }}</td>
                            <td class="px-4 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase 
                                    {{ $b->status == 'Tersedia' ? 'bg-blue-500 text-white' : 'bg-orange-400 text-white' }}">
                                    {{ $b->status }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right flex justify-end space-x-1">
                                <button onclick="openEditModal({{ $b->id }}, '{{ $b->nama_barang }}', '{{ $b->kategori }}', '{{ $b->kondisi }}', '{{ $b->lokasi }}', '{{ $b->deskripsi }}')" class="text-blue-500 p-2 hover:bg-blue-50 rounded-lg">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </button>
                                <form action="{{ route('barang.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Hapus barang ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 p-2 hover:bg-red-50 rounded-lg">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="text-center py-10 text-gray-400 italic font-medium">Data tidak ditemukan...</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @endforeach
        </div>
    </div>

    <div id="modalTambah" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm items-center justify-center z-[100] p-4">
        <div class="bg-white p-8 rounded-3xl w-full max-w-2xl shadow-2xl">
            <h3 class="text-xl font-bold mb-6">Tambah Inventaris Baru</h3>
            <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-2 gap-4">
                @csrf
                <div class="col-span-2">
                    <label class="text-xs font-bold text-gray-400 uppercase">Foto Barang</label>
                    <input type="file" name="foto" class="w-full border rounded-xl p-2 bg-gray-50 text-sm mt-1">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-400 uppercase">Kode Barang</label>
                    <input type="text" name="kode_barang" class="w-full border rounded-xl p-3 mt-1" required>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-400 uppercase">Nama Barang</label>
                    <input type="text" name="nama_barang" class="w-full border rounded-xl p-3 mt-1" required>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Pilih Kategori</label>
                    <select name="kategori_id" required 
                        class="w-full bg-gray-50 border-gray-100 rounded-2xl text-sm focus:ring-indigo-500 transition-all">
                        <option value="" disabled selected>-- Pilih Kategori --</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-400 uppercase">Kondisi</label>
                    <select name="kondisi" class="w-full border rounded-xl p-3 mt-1">
                        <option value="Baik">Baik</option>
                        <option value="Rusak">Rusak</option>
                        <option value="Perbaikan">Perbaikan</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="text-xs font-bold text-gray-400 uppercase text-blue-600">Lokasi Penyimpanan</label>
                    <input type="text" name="lokasi" class="w-full border-blue-100 rounded-xl p-3 mt-1 bg-blue-50/20" placeholder="Contoh: Lab PPLG 1" required>
                </div>
                <div class="col-span-2">
                    <label class="text-xs font-bold text-gray-400 uppercase">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="w-full border rounded-xl p-3 mt-1" placeholder="Catatan tambahan..."></textarea>
                </div>
                <div class="col-span-2 flex justify-end space-x-3 mt-4">
                    <button type="button" onclick="closeModal('modalTambah')" class="text-gray-400 font-bold">Batal</button>
                    <button type="submit" class="bg-[#2f77dd] text-white px-8 py-3 rounded-xl font-bold shadow-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalEdit" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm items-center justify-center z-[100] p-4">
        <div class="bg-white p-8 rounded-3xl w-full max-w-2xl shadow-2xl border border-gray-100">
            <h3 class="text-xl font-bold mb-6 text-purple-600">Edit Data Inventaris</h3>
            <form id="formEdit" method="POST" enctype="multipart/form-data" class="grid grid-cols-2 gap-4">
                @csrf @method('PUT')
                <div class="col-span-2">
                    <label class="text-xs font-bold text-gray-400 uppercase">Nama Barang</label>
                    <input type="text" name="nama_barang" id="edit_nama" class="w-full border rounded-xl p-3 mt-1" required>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-400 uppercase">Kondisi</label>
                    <select name="kondisi" id="edit_kondisi" class="w-full border rounded-xl p-3 mt-1">
                        <option value="Baik">Baik</option>
                        <option value="Rusak">Rusak</option>
                        <option value="Perbaikan">Perbaikan</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-400 uppercase">Lokasi</label>
                    <input type="text" name="lokasi" id="edit_lokasi" class="w-full border rounded-xl p-3 mt-1" required>
                </div>
                <div class="col-span-2">
                    <label class="text-xs font-bold text-purple-400 uppercase">Deskripsi</label>
                    <textarea name="deskripsi" id="edit_deskripsi" rows="3" class="w-full border-purple-100 rounded-xl p-3 mt-1 bg-purple-50/10"></textarea>
                </div>
                <div class="col-span-2 flex justify-end space-x-3 mt-4">
                    <button type="button" onclick="closeModal('modalEdit')" class="px-4 py-2 text-gray-400 font-bold">Batal</button>
                    <button type="submit" class="bg-[#7c4dc2] text-white px-8 py-3 rounded-xl font-bold shadow-lg">Update</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalPreview" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm items-center justify-center z-[110] p-4" onclick="closeModal('modalPreview')">
        <div class="max-w-xl w-full">
            <img id="imgPreview" src="" class="w-full h-auto rounded-3xl shadow-2xl border-4 border-white">
            <p class="text-white text-center mt-4 font-bold">Klik dimana saja untuk menutup</p>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.getElementById('tab-' + tab).classList.remove('hidden');
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'shadow-sm', 'text-blue-600');
                btn.classList.add('text-gray-500');
            });
            const activeBtn = document.getElementById('btn-' + tab);
            activeBtn.classList.add('bg-white', 'shadow-sm', 'text-blue-600');
            activeBtn.classList.remove('text-gray-500');
        }

        function openModal(id) {
            const modal = document.getElementById(id);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function showPreview(src) {
            document.getElementById('imgPreview').src = src;
            openModal('modalPreview');
        }

        function openEditModal(id, nama, kategori, kondisi, lokasi, deskripsi) {
            document.getElementById('formEdit').action = "{{ url('admin/barang') }}/" + id;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_kondisi').value = kondisi;
            document.getElementById('edit_lokasi').value = lokasi;
            document.getElementById('edit_deskripsi').value = deskripsi || '';
            openModal('modalEdit');
        }
    </script>
</x-app-layout>