<x-app-layout>
    <x-slot name="header">Data Petugas & Admin</x-slot>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-50 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-bold text-[#272b34]">Daftar Pengguna Sistem</h3>
                <p class="text-sm text-gray-400">Manajemen akun Admin dan Petugas PPLG</p>
            </div>
            <button onclick="openModal('modalTambah')" 
                    class="bg-[#2f77dd] hover:bg-[#7c4dc2] text-white px-6 py-3 rounded-xl font-bold transition flex items-center shadow-lg shadow-blue-500/20">
                <i data-lucide="user-plus" class="w-5 h-5 mr-2"></i>
                Tambah Petugas
            </button>
        </div>

        <div class="overflow-x-auto p-4">
            <table class="w-full text-left border-separate border-spacing-y-2">
                <thead>
                    <tr class="text-[#272b34] uppercase text-[11px] tracking-widest font-extrabold">
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4 text-center">Role</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($petugas as $p)
                    <tr class="bg-gray-50 hover:bg-white hover:shadow-md transition rounded-2xl overflow-hidden">
                        <td class="px-6 py-4 font-bold text-[#272b34]">{{ $p->name }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $p->email }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $p->role == 'admin' ? 'bg-[#7c4dc2]/10 text-[#7c4dc2]' : 'bg-[#2f77dd]/10 text-[#2f77dd]' }}">
                                {{ $p->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right flex justify-end space-x-2">
                            <button onclick="openEditModal({{ $p->id }}, '{{ $p->name }}', '{{ $p->email }}', '{{ $p->role }}')" 
                                    class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </button>
                            
                            <form action="{{ route('petugas.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus petugas ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-10 text-gray-400 font-medium italic">Belum ada data petugas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="modalTambah" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-[99] p-4">
        <div class="bg-white p-8 rounded-3xl w-full max-w-md shadow-2xl border border-gray-100">
            <h3 class="text-xl font-bold mb-6 text-[#272b34]">Tambah Petugas Baru</h3>
            <form action="{{ route('petugas.store') }}" method="POST" class="space-y-4" autocomplete="off">
                @csrf
                <input autocomplete="false" name="hidden" type="text" style="display:none;">
                
                <div>
                    <label class="text-xs font-bold text-gray-400 uppercase ml-1 tracking-wider">Nama Lengkap</label>
                    <input type="text" name="name" class="w-full border-gray-100 rounded-xl bg-gray-50 p-3 mt-1 focus:ring-2 focus:ring-[#2f77dd] focus:bg-white outline-none transition" required>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-400 uppercase ml-1 tracking-wider">Email Address</label>
                    <input type="email" name="email" class="w-full border-gray-100 rounded-xl bg-gray-50 p-3 mt-1 focus:ring-2 focus:ring-[#2f77dd] focus:bg-white outline-none transition" required autocomplete="off">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-400 uppercase ml-1 tracking-wider">Password</label>
                    <input type="password" name="password" class="w-full border-gray-100 rounded-xl bg-gray-50 p-3 mt-1 focus:ring-2 focus:ring-[#2f77dd] focus:bg-white outline-none transition" required autocomplete="new-password">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-400 uppercase ml-1 tracking-wider">Role Akses</label>
                    <select name="role" class="w-full border-gray-100 rounded-xl bg-gray-50 p-3 mt-1 font-semibold focus:ring-2 focus:ring-[#2f77dd] outline-none transition">
                        <option value="petugas">Petugas</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal('modalTambah')" class="px-4 py-2 text-gray-400 font-bold hover:text-gray-600 transition">Batal</button>
                    <button type="submit" class="bg-[#2f77dd] text-white px-8 py-2 rounded-xl font-bold shadow-lg shadow-blue-500/20 hover:bg-[#1e5bb8] transition">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalEdit" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-[99] p-4">
        <div class="bg-white p-8 rounded-3xl w-full max-w-md shadow-2xl border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-[#272b34]">Edit Data Petugas</h3>
                <span id="labelEditID" class="text-[10px] bg-gray-100 px-2 py-1 rounded text-gray-400 font-mono">ID: -</span>
            </div>
            <form id="formEdit" method="POST" class="space-y-4" autocomplete="off">
                @csrf 
                @method('PUT')
                <div>
                    <label class="text-xs font-bold text-gray-400 uppercase ml-1 tracking-wider">Nama Lengkap</label>
                    <input type="text" name="name" id="edit_name" class="w-full border-gray-100 rounded-xl bg-gray-50 p-3 mt-1 focus:ring-2 focus:ring-[#7c4dc2] focus:bg-white outline-none transition" required>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-400 uppercase ml-1 tracking-wider">Email Address</label>
                    <input type="email" name="email" id="edit_email" class="w-full border-gray-100 rounded-xl bg-gray-50 p-3 mt-1 focus:ring-2 focus:ring-[#7c4dc2] focus:bg-white outline-none transition" required>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-400 uppercase ml-1 tracking-wider">Password Baru</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak ganti" class="w-full border-gray-100 rounded-xl bg-gray-50 p-3 mt-1 focus:ring-2 focus:ring-[#7c4dc2] focus:bg-white outline-none transition" autocomplete="new-password">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-400 uppercase ml-1 tracking-wider">Role Akses</label>
                    <select name="role" id="edit_role" class="w-full border-gray-100 rounded-xl bg-gray-50 p-3 mt-1 font-semibold focus:ring-2 focus:ring-[#7c4dc2] outline-none transition">
                        <option value="petugas">Petugas</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal('modalEdit')" class="px-4 py-2 text-gray-400 font-bold hover:text-gray-600 transition">Batal</button>
                    <button type="submit" class="bg-[#7c4dc2] text-white px-8 py-2 rounded-xl font-bold shadow-lg shadow-purple-500/20 hover:bg-[#62399e] transition">Update Data</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            modal.classList.remove('hidden');
            modal.classList.add('flex'); // Pastikan pakai flex agar centering jalan
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            
            // Jika menutup modal tambah, reset form-nya biar bersih
            if(id === 'modalTambah') {
                modal.querySelector('form').reset();
            }
        }

        function openEditModal(id, name, email, role) {
    const form = document.getElementById('formEdit');
    
    // Pastikan penulisannya rapat: {{ url('path') }}
    // Kita pakai tanda kutip biasa saja supaya lebih aman dari error Blade
    form.action = "{{ url('admin/petugas') }}/" + id; 

    document.getElementById('edit_name').value = name;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_role').value = role;

    openModal('modalEdit');
}

        // Close modal on click backdrop
        window.addEventListener('click', function(e) {
            if (e.target.id === 'modalTambah') closeModal('modalTambah');
            if (e.target.id === 'modalEdit') closeModal('modalEdit');
        });
    </script>
</x-app-layout>