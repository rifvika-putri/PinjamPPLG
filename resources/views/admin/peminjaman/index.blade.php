<x-app-layout>
    <div x-data="{ showModal: false, imgSource: '', imgTitle: '' }" class="space-y-6">
        
        <x-slot name="header">Data Peminjaman</x-slot>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-[#272b34]">Log Peminjaman Barang</h2>
                <p class="text-sm text-gray-400">Kelola dan pantau alur peminjaman alat sekolah</p>
            </div>
            
            <div class="relative group">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-500 transition-colors"></i>
                <input type="text" id="searchInput" placeholder="Cari nama siswa atau barang..." 
                    class="pl-10 pr-4 py-2.5 bg-white border border-gray-100 rounded-2xl text-sm w-full md:w-80 shadow-sm focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
            </div>
        </div>

        <div class="flex p-1.5 bg-gray-100/50 rounded-2xl w-fit border border-gray-100">
            <button onclick="filterTab('all')" class="tab-btn active-tab px-6 py-2 rounded-xl text-xs font-bold transition-all">Semua</button>
            <button onclick="filterTab('pending')" class="tab-btn px-6 py-2 rounded-xl text-xs font-bold text-gray-400 hover:text-gray-600 transition-all">Pending</button>
            <button onclick="filterTab('dipinjam')" class="tab-btn px-6 py-2 rounded-xl text-xs font-bold text-gray-400 hover:text-gray-600 transition-all">Sedang Dipinjam</button>
            <button onclick="filterTab('selesai')" class="tab-btn px-6 py-2 rounded-xl text-xs font-bold text-gray-400 hover:text-gray-600 transition-all">Selesai</button>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left" id="peminjamanTable">
                <thead class="bg-gray-50 text-[10px] uppercase font-extrabold tracking-widest text-gray-400">
                    <tr>
                        <th class="px-6 py-4">Peminjam</th>
                        <th class="px-6 py-4">Barang</th>
                        <th class="px-6 py-4 text-center">Bukti & Kondisi</th>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($peminjamans as $p)
                    <tr class="peminjaman-row hover:bg-gray-50/50 transition cursor-default" data-status="{{ $p->status }}">
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900">{{ $p->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $p->user->kelas ?? 'XII PPLG 2' }}</div>
                            <div class="text-[10px] font-medium text-indigo-600 mt-1 flex items-center">
                                <i data-lucide="phone" class="w-3 h-3 mr-1"></i>
                                {{ $p->user->no_telp ?? '-' }}
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    @if($p->barang->foto_barang)
                                        <img src="{{ asset('storage/' . $p->barang->foto_barang) }}" 
                                            @click="showModal = true; imgSource = '{{ asset('storage/' . $p->barang->foto_barang) }}'; imgTitle = 'Detail Barang - {{ $p->barang->nama_barang }}'"
                                            class="w-10 h-10 object-cover rounded-xl border border-gray-100 shadow-inner cursor-pointer hover:scale-105 transition"
                                            alt="Foto Barang">
                                    @else
                                        <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center border border-gray-200">
                                            <i data-lucide="package" class="w-5 h-5 text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <div class="text-sm font-bold text-gray-900 leading-tight">
                                        {{ $p->barang->nama_barang }}
                                    </div>
                                    <div class="flex items-center space-x-1.5 mt-0.5">
                                        <span class="px-1.5 py-0.5 text-[10px] bg-gray-50 border border-gray-100 rounded-md text-gray-500 font-mono tracking-tighter">
                                            {{ $p->barang->kode_barang }}
                                        </span>
                                        <span class="text-[10px] font-medium {{ $p->kondisi_pinjam == 'baik' ? 'text-green-600' : 'text-amber-600' }}">
                                            Cond: {{ $p->kondisi_pinjam }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center space-x-3">
                                <div class="text-center group">
                                    <img src="{{ asset('uploads/bukti_peminjaman/' . $p->foto_pinjam) }}" 
                                        @click="showModal = true; imgSource = '{{ asset('uploads/bukti_peminjaman/' . $p->foto_pinjam) }}'; imgTitle = 'Bukti Foto Pinjam - {{ $p->user->name }}'"
                                        class="w-12 h-12 object-cover rounded-lg border cursor-pointer hover:scale-110 transition shadow-sm"
                                        alt="Bukti Pinjam">
                                    <div class="text-[8px] mt-1 font-bold text-gray-400 uppercase">PINJAM</div>
                                </div>

                                @if($p->foto_kembali)
                                <div class="text-center group">
                                    <img src="{{ asset('storage/'.$p->foto_kembali) }}" 
                                        @click="showModal = true; imgSource = '{{ asset('storage/'.$p->foto_kembali) }}'; imgTitle = 'Bukti Foto Kembali - {{ $p->user->name }}'"
                                        class="w-12 h-12 object-cover rounded-lg border-2 border-white shadow-sm ring-1 ring-blue-100 cursor-pointer hover:scale-110 transition"
                                        alt="Bukti Kembali">
                                    <div class="text-[8px] mt-1 font-bold {{ $p->kondisi_kembali == 'Rusak' ? 'text-red-500' : 'text-blue-500' }} uppercase">
                                        {{ $p->kondisi_kembali }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4 text-[11px] text-gray-500">
                            <p><span class="text-gray-300 font-bold mr-1">IN</span> {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m H:i') }}</p>
                            <p class="text-red-400 font-bold"><span class="text-red-200 font-bold mr-1">OUT</span> {{ \Carbon\Carbon::parse($p->tanggal_kembali_rencana)->format('H:i') }} WIB</p>
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter
                                {{ $p->status == 'pending' ? 'bg-amber-100 text-amber-600 border border-amber-200' : 
                                    ($p->status == 'dipinjam' ? 'bg-blue-100 text-blue-600 border border-blue-200' : 
                                    ($p->status == 'menunggu verifikasi' ? 'bg-purple-100 text-purple-600 border border-purple-200 animate-pulse' : 'bg-emerald-100 text-emerald-600 border border-emerald-200')) }}">
                                {{ $p->status }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                {{-- LOGIKA LINK WHATSAPP --}}
                                @if($p->status != 'selesai')
                                    @php
                                        $no_hp = preg_replace('/[^0-9]/', '', $p->user->no_telp);
                                        if (str_starts_with($no_hp, '0')) { $no_hp = '62' . substr($no_hp, 1); }

                                        $pesan = "Halo " . $p->user->name . ",\n\n" .
                                                 "Ini pengingat dari *Sisapras* untuk barang:\n" .
                                                 "- *Barang:* " . $p->barang->nama_barang . "\n" .
                                                 "- *Kode:* " . $p->barang->kode_barang . "\n" .
                                                 "- *Harus Kembali:* " . \Carbon\Carbon::parse($p->tanggal_kembali_rencana)->format('d/m/Y H:i') . " WIB\n\n" .
                                                 "Mohon segera dikembalikan jika sudah selesai ya. Terima kasih!";
                                        
                                        $waUrl = "https://wa.me/" . $no_hp . "?text=" . urlencode($pesan);
                                    @endphp

                                    <a href="{{ $waUrl }}" target="_blank"
                                       class="flex items-center bg-emerald-100 hover:bg-emerald-200 text-emerald-700 px-3 py-2 rounded-xl text-[10px] font-bold transition-all border border-emerald-200">
                                        <i data-lucide="message-circle" class="w-3.5 h-3.5 mr-1"></i>
                                        WA NOTIF
                                    </a>
                                @endif

                                @if($p->status == 'pending')
                                    <form action="{{ route('peminjaman.setujui', $p->id) }}" method="POST">
                                        @csrf
                                        <button class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-xl text-[10px] font-bold shadow-md shadow-emerald-100 transition-all">SETUJUI</button>
                                    </form>
                                @elseif($p->status == 'menunggu verifikasi')
                                    <button onclick="confirmSelesai({{ $p->id }})" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-[10px] font-bold shadow-md shadow-indigo-100 transition-all">VERIFIKASI</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-20 text-center text-gray-400 italic">Belum ada data peminjaman...</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Modal Preview Foto --}}
        <div x-show="showModal" 
             class="fixed inset-0 z-[999] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @keydown.escape.window="showModal = false"
             style="display: none;">
            
            <div class="relative bg-white p-2 rounded-2xl max-w-2xl w-full shadow-2xl" @click.away="showModal = false">
                <button @click="showModal = false" class="absolute -top-12 right-0 text-white hover:text-red-400 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                
                <div class="absolute -top-10 left-0 text-white font-bold text-sm tracking-wide" x-text="imgTitle"></div>
                
                <img :src="imgSource" class="w-full h-auto max-h-[85vh] object-contain rounded-xl shadow-inner bg-gray-50">
            </div>
        </div>

    </div> 
    
    <style>
        .active-tab {
            background: white;
            color: #4f46e5;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }
    </style>

    <script>
        // Logika Search
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('.peminjaman-row');
            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });

        // Logika Tab Filter
        function filterTab(status) {
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active-tab');
                btn.classList.add('text-gray-400');
            });
            event.target.classList.add('active-tab');
            event.target.classList.remove('text-gray-400');

            let rows = document.querySelectorAll('.peminjaman-row');
            rows.forEach(row => {
                if (status === 'all') {
                    row.style.display = '';
                } else {
                    row.style.display = row.getAttribute('data-status') === status ? '' : 'none';
                }
            });
        }

        function confirmSelesai(id) {
            if(confirm('Pastikan kondisi barang sudah dicek fisik.')) {
                window.location.href = "/admin/peminjaman/selesaikan/" + id;
            }
        }
    </script>
</x-app-layout>