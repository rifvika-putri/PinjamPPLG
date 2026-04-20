<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Data Petugas & Admin
        </h2>
    </x-slot>

    <div class="py-12 bg-[#f8fafc]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Header Section --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Daftar Pengguna Sistem</h3>
                    <p class="text-sm text-gray-500 mt-1">Manajemen akun Admin dan Petugas untuk sistem BarangSekolah.</p>
                </div>
                <button onclick="openModal('modalTambah')" 
                        class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-2xl transition-all duration-300 shadow-lg shadow-blue-200 group">
                    <i data-lucide="user-plus" class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform"></i>
                    Tambah Petugas Baru
                </button>
            </div>

            {{-- Table Section --}}
            <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                
                <div class="p-6 border-b border-gray-50 bg-white/50 backdrop-blur-sm flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="relative w-full sm:w-80">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </span>
                        <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Cari nama atau email..." 
                               class="block w-full pl-10 pr-4 py-2.5 border-none bg-gray-50 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 transition-all placeholder:text-gray-400 font-medium">
                    </div>
                    <div class="text-sm font-bold text-blue-600 bg-blue-50 px-4 py-2 rounded-lg">
                        {{ $petugas->count() }} Total Pengguna
                    </div>
                </div>

                <div class="overflow-x-auto p-4">
                    <table class="w-full text-left border-separate border-spacing-y-3" id="petugasTable">
                        <thead>
                            <tr class="text-gray-400 uppercase text-[11px] tracking-[0.15em] font-black">
                                <th class="px-6 py-3">Nama & Kontak</th>
                                <th class="px-6 py-3">Jadwal Kerja</th>
                                <th class="px-6 py-3 text-center">Akses Role</th>
                                <th class="px-6 py-3 text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($petugas as $p)
                            <tr class="group hover:translate-y-[-2px] transition-all duration-300">
                                <td class="px-6 py-4 bg-gray-50/50 group-hover:bg-white group-hover:shadow-md rounded-l-2xl border-y border-l border-transparent group-hover:border-gray-100 transition-all">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-md">
                                            {{ strtoupper(substr($p->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-800">{{ $p->name }}</div>
                                            <div class="text-[11px] text-gray-400 font-medium">{{ $p->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 bg-gray-50/50 group-hover:bg-white group-hover:shadow-md border-y border-transparent group-hover:border-gray-100 transition-all text-sm font-semibold text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-blue-400"></i>
                                        {{ $p->jadwal_kerja ?? 'Belum Diatur' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 bg-gray-50/50 group-hover:bg-white group-hover:shadow-md border-y border-transparent group-hover:border-gray-100 transition-all text-center">
                                    @if($p->role == 'admin')
                                        <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-purple-50 text-purple-600 border border-purple-100">ADMIN</span>
                                    @else
                                        <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-blue-50 text-blue-600 border border-blue-100">PETUGAS</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 bg-gray-50/50 group-hover:bg-white group-hover:shadow-md rounded-r-2xl border-y border-r border-transparent group-hover:border-gray-100 transition-all text-right">
                                    <div class="flex justify-end gap-2">
                                        <button type="button" onclick="showDetail('{{ $p->name }}', '{{ $p->email }}', '{{ $p->role }}', '{{ $p->jadwal_kerja }}')" 
                                                class="p-2.5 text-emerald-500 hover:bg-emerald-50 rounded-xl transition-colors">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </button>

                                        <button type="button" onclick="openEditModal('{{ url('admin/petugas') }}/{{ $p->id }}', '{{ $p->name }}', '{{ $p->email }}', '{{ $p->role }}', '{{ $p->jadwal_kerja }}')" 
                                                class="p-2.5 text-blue-500 hover:bg-blue-50 rounded-xl transition-colors">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>

                                        <form action="{{ route('petugas.destroy', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pengguna ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2.5 text-red-500 hover:bg-red-50 rounded-xl transition-colors">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-20 text-center text-gray-400 italic font-medium">Data tidak ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Error Notifications --}}
    @if ($errors->any())
        <div class="fixed bottom-4 right-4 z-[10000] w-80">
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-xl shadow-lg">
                <ul class="text-sm text-red-600 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- MODAL TAMBAH --}}
    <div id="modalTambah" class="hidden fixed inset-0 z-[9999] bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-2xl transition-all">
            <div class="bg-blue-600 p-6 text-white flex justify-between items-center">
                <h3 class="font-bold text-lg">Tambah Petugas Baru</h3>
                <button onclick="closeModal('modalTambah')" class="text-white hover:opacity-70 text-2xl">&times;</button>
            </div>
            
            <form action="{{ route('petugas.store') }}" method="POST" class="p-8 space-y-4">
                @csrf
                
                {{-- Nama --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full bg-gray-50 border-none rounded-2xl py-3 px-4 text-sm focus:ring-2 focus:ring-blue-500/20 font-semibold">
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Email</label>
                    <input type="email" name="email" required class="w-full bg-gray-50 border-none rounded-2xl py-3 px-4 text-sm focus:ring-2 focus:ring-blue-500/20 font-semibold">
                </div>

                {{-- Password & Role --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Password</label>
                        <input type="password" name="password" required class="w-full bg-gray-50 border-none rounded-2xl py-3 px-4 text-sm focus:ring-2 focus:ring-blue-500/20 font-semibold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Role</label>
                        <select name="role" class="w-full bg-gray-50 border-none rounded-2xl py-3 px-4 text-sm focus:ring-2 focus:ring-blue-500/20 font-semibold">
                            <option value="petugas">Petugas</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>

                {{-- Hari Tugas (Checkbox) --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Hari Tugas</label>
                    <div class="grid grid-cols-3 gap-2 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $hari)
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" name="hari[]" value="{{ $hari }}" class="rounded text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span class="text-xs font-semibold text-gray-600 group-hover:text-blue-600 transition-colors">{{ $hari }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Jam Tugas --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Jam Mulai</label>
                        <input type="time" name="jam_mulai" required class="w-full bg-gray-50 border-none rounded-2xl py-3 px-4 text-sm focus:ring-2 focus:ring-blue-500/20 font-semibold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Jam Selesai</label>
                        <input type="time" name="jam_selesai" required class="w-full bg-gray-50 border-none rounded-2xl py-3 px-4 text-sm focus:ring-2 focus:ring-blue-500/20 font-semibold">
                    </div>
                </div>

                <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-xs uppercase tracking-widest shadow-lg shadow-blue-200 transition-all mt-4">
                    Simpan Petugas
                </button>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT --}}
<div id="modalEdit" class="hidden fixed inset-0 z-[9999] bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-2xl">
        <div class="bg-indigo-600 p-6 text-white flex justify-between items-center">
            <h3 class="font-bold text-lg">Edit Data Petugas</h3>
            <button onclick="closeModal('modalEdit')" class="text-white hover:opacity-70 text-2xl">&times;</button>
        </div>
        
        <form id="editForm" method="POST" class="p-8 space-y-4">
            @csrf @method('PUT')
            
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Nama Lengkap</label>
                <input type="text" id="edit_name" name="name" required class="w-full bg-gray-50 border-none rounded-2xl py-3 px-4 text-sm focus:ring-2 focus:ring-indigo-500/20 font-semibold">
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Email</label>
                <input type="email" id="edit_email" name="email" required class="w-full bg-gray-50 border-none rounded-2xl py-3 px-4 text-sm focus:ring-2 focus:ring-indigo-500/20 font-semibold">
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Role</label>
                <select id="edit_role" name="role" class="w-full bg-gray-50 border-none rounded-2xl py-3 px-4 text-sm focus:ring-2 focus:ring-indigo-500/20 font-semibold">
                    <option value="petugas">Petugas</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            {{-- Hari Tugas (Checkbox) --}}
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Hari Tugas</label>
                <div class="grid grid-cols-3 gap-2 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $hari)
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="hari[]" value="{{ $hari }}" class="edit-hari-checkbox rounded text-indigo-600 focus:ring-indigo-500 border-gray-300">
                            <span class="text-xs font-semibold text-gray-600 group-hover:text-indigo-600 transition-colors">{{ $hari }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Jam Tugas --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Jam Mulai</label>
                    <input type="time" id="edit_jam_mulai" name="jam_mulai" required class="w-full bg-gray-50 border-none rounded-2xl py-3 px-4 text-sm focus:ring-2 focus:ring-indigo-500/20 font-semibold">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Jam Selesai</label>
                    <input type="time" id="edit_jam_selesai" name="jam_selesai" required class="w-full bg-gray-50 border-none rounded-2xl py-3 px-4 text-sm focus:ring-2 focus:ring-indigo-500/20 font-semibold">
                </div>
            </div>

            <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-xs uppercase tracking-widest shadow-lg shadow-indigo-100 transition-all mt-4">
                Update Perubahan
            </button>
        </form>
    </div>
</div>

    {{-- MODAL DETAIL --}}
    <div id="modalDetail" class="hidden fixed inset-0 z-[9999] bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-[2.5rem] w-full max-w-sm overflow-hidden shadow-2xl">
            <div class="h-24 bg-gradient-to-r from-blue-600 to-indigo-700"></div>
            <div class="px-8 pb-8 text-center">
                <div class="relative flex justify-center">
                    <div id="detail_avatar" class="-mt-12 w-24 h-24 bg-white rounded-3xl shadow-lg flex items-center justify-center text-3xl font-bold text-blue-600 border-4 border-white">??</div>
                </div>
                <h3 id="detail_name" class="text-xl font-bold text-gray-900 mt-4">Nama Petugas</h3>
                <p id="detail_role" class="text-[10px] font-black uppercase tracking-widest text-blue-500 mt-2 bg-blue-50 inline-block px-3 py-1 rounded-lg">ROLE</p>
                
                <div class="mt-8 space-y-3 text-left">
                    <div class="flex items-center p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <i data-lucide="mail" class="w-5 h-5 text-gray-400 mr-4"></i>
                        <span id="detail_email" class="text-sm text-gray-600 font-semibold truncate"></span>
                    </div>
                    <div class="flex items-center p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <i data-lucide="calendar" class="w-5 h-5 text-gray-400 mr-4"></i>
                        <span id="detail_jadwal" class="text-sm text-gray-600 font-semibold"></span>
                    </div>
                </div>
                <button onclick="closeModal('modalDetail')" class="w-full mt-8 py-4 bg-gray-900 text-white text-sm font-bold rounded-2xl shadow-lg">Tutup Detail</button>
            </div>
        </div>
    </div>

    <script>
        // Modal Handlers
        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function showDetail(name, email, role, jadwal) {
            document.getElementById('detail_name').innerText = name;
            document.getElementById('detail_email').innerText = email;
            document.getElementById('detail_role').innerText = role.toUpperCase();
            document.getElementById('detail_jadwal').innerText = jadwal || 'Belum diatur';
            document.getElementById('detail_avatar').innerText = name.substring(0, 2).toUpperCase();
            openModal('modalDetail');
        }

        function openEditModal(actionUrl, name, email, role, hari, jamMulai, jamSelesai) {
        const form = document.getElementById('editForm');
        if (!form) return; // Keamanan tambahan
        
        form.action = actionUrl;
        
        // Gunakan pengecekan (el) agar jika ID tidak ada, tidak bikin error satu halaman
        const elName = document.getElementById('edit_name');
        const elEmail = document.getElementById('edit_email');
        const elRole = document.getElementById('edit_role');
        const elJamMulai = document.getElementById('edit_jam_mulai');
        const elJamSelesai = document.getElementById('edit_jam_selesai');

        if (elName) elName.value = name;
        if (elEmail) elEmail.value = email;
        if (elRole) elRole.value = role;
        if (elJamMulai) elJamMulai.value = jamMulai || '';
        if (elJamSelesai) elJamSelesai.value = jamSelesai || '';

        // Bagian Checkbox Hari
        const checkboxes = document.querySelectorAll('.edit-hari-checkbox');
        checkboxes.forEach(cb => cb.checked = false);

        if (hari) {
            const selectedHari = hari.split(','); 
            checkboxes.forEach(cb => {
                if (selectedHari.includes(cb.value)) {
                    cb.checked = true;
                }
            });
        }

        openModal('modalEdit');
    }

        // Table Search
        function searchTable() {
            let input = document.getElementById("searchInput");
            let filter = input.value.toUpperCase();
            let table = document.getElementById("petugasTable");
            let tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) {
                let tdName = tr[i].getElementsByTagName("td")[0];
                if (tdName) {
                    let txtValue = tdName.textContent || tdName.innerText;
                    tr[i].style.display = txtValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
                }
            }
        }
    </script>
</x-app-layout>