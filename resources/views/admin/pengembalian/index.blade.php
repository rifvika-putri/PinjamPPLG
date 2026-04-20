<x-app-layout>
    <x-slot name="header">Verifikasi Pengembalian</x-slot>

    <div x-data="{ 
        showModal: false, 
        selectedId: '', 
        selectedBarang: '', 
        selectedKode: '',
        deadline: '',
        dendaTelat: 0,
        dendaKerusakan: 0,
        kondisi: 'Baik',
        hitungDenda() {
            const tglDeadline = new Date(this.deadline);
            const sekarang = new Date();
            if (sekarang > tglDeadline) {
                const selisihMenit = Math.floor((sekarang - tglDeadline) / 60000);
                const kelipatan = Math.ceil(selisihMenit / 10);
                this.dendaTelat = kelipatan * 5000;
            } else {
                this.dendaTelat = 0;
            }
        },
        get totalDenda() {
            return (parseInt(this.dendaTelat) || 0) + (parseInt(this.dendaKerusakan) || 0);
        }
    }" class="space-y-6">
        
        <div class="flex justify-between items-center px-4">
            <div>
                <h2 class="text-2xl font-bold text-[#272b34]">Pengembalian Barang</h2>
                <p class="text-sm text-gray-400">Data audit lengkap peminjaman dan pengembalian</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[1100px]">
                    <thead class="bg-gray-50 text-[10px] uppercase font-extrabold tracking-widest text-gray-400">
                        <tr>
                            <th class="px-6 py-4">Siswa</th>
                            <th class="px-6 py-4">Data Barang</th>
                            <th class="px-6 py-4">Timeline & Status</th>
                            <th class="px-6 py-4">Catatan & Kondisi</th>
                            <th class="px-6 py-4">Rincian Denda</th>
                            <th class="px-6 py-4 text-center">Status Bayar</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pengembalians as $p)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900">{{ $p->user->name }}</div>
                                <div class="text-[10px] text-gray-500 font-medium">Kelas: {{ $p->user->kelas }}</div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset('storage/barang/' . $p->barang->foto) }}" 
                                         class="w-10 h-10 object-cover rounded-xl border border-gray-100">
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $p->barang->nama_barang }}</div>
                                        <div class="text-[10px] text-indigo-500 font-mono font-bold">{{ $p->barang->kode_barang }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="space-y-1 text-[9px]">
                                    <div class="text-gray-400 font-bold">TARGET: <span class="text-gray-700">{{ \Carbon\Carbon::parse($p->tanggal_kembali_rencana)->format('d/m/y H:i') }}</span></div>
                                    <div class="text-gray-400 font-bold">REAL: <span class="text-gray-700">{{ $p->tanggal_kembali_realisasi ? \Carbon\Carbon::parse($p->tanggal_kembali_realisasi)->format('d/m/y H:i') : '-' }}</span></div>
                                    
                                    @if($p->tanggal_kembali_realisasi)
                                        @php
                                            $terlambat = \Carbon\Carbon::parse($p->tanggal_kembali_realisasi)->gt(\Carbon\Carbon::parse($p->tanggal_kembali_rencana));
                                        @endphp
                                        <span class="inline-block px-2 py-0.5 rounded text-[8px] font-black uppercase {{ $terlambat ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                            {{ $terlambat ? 'Terlambat' : 'Tepat Waktu' }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-[10px] font-bold text-gray-700">Kondisi: {{ $p->kondisi_kembali ?? 'Proses' }}</div>
                                <div class="text-[10px] text-gray-400 italic">{{ Str::limit($p->catatan_kerusakan ?? '-', 20) }}</div>
                            </td>

                            <td class="px-6 py-4">
                                @if($p->total_denda > 0)
                                    <div class="text-[10px] font-bold text-red-600">Rp{{ number_format($p->total_denda) }}</div>
                                @else
                                    <span class="text-[10px] text-gray-300">-</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($p->status_pembayaran == 'Lunas')
                                    <span class="bg-green-100 text-green-600 px-2 py-1 rounded-lg text-[8px] font-black">LUNAS</span>
                                @elseif($p->status_pembayaran == 'PENDING')
                                    <span class="bg-amber-100 text-amber-600 px-2 py-1 rounded-lg text-[8px] font-black animate-pulse">PENDING</span>
                                @elseif($p->total_denda > 0)
                                    <span class="bg-red-100 text-red-600 px-2 py-1 rounded-lg text-[8px] font-black">BELUM BAYAR</span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex flex-col items-end gap-2">
                                    {{-- LOGIKA AKSI DIMULAI --}}
                                    @if($p->status == 'kembalikan_pending')
                                        @if($p->status_pembayaran == 'PENDING')
                                            {{-- Siswa sudah bayar, Admin Verifikasi Uang --}}
                                            <a href="{{ asset('uploads/bukti_denda/' . $p->bukti_pembayaran) }}" target="_blank" class="text-[9px] text-indigo-600 font-bold uppercase underline">
                                                Cek Bukti Transfer
                                            </a>
                                            <form action="{{ route('admin.peminjaman.verifikasi-denda', $p->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-emerald-500 text-white px-3 py-1.5 rounded-xl text-[10px] font-black shadow-md">
                                                    KONFIRMASI LUNAS
                                                </button>
                                            </form>
                                        @else
                                            {{-- Barang baru sampai, Admin Cek Kondisi & Input Denda --}}
                                            <button @click="showModal = true; selectedId = '{{ $p->id }}'; selectedBarang = '{{ $p->barang->nama_barang }}'; selectedKode = '{{ $p->barang->kode_barang }}'; deadline = '{{ $p->tanggal_kembali_rencana }}'; hitungDenda()"
                                                    class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-[10px] font-bold shadow-md">
                                                VERIFIKASI
                                            </button>
                                        @endif
                                    @elseif($p->status == 'selesai')
                                        <span class="text-emerald-500 font-black text-[10px] uppercase tracking-widest">✔ DONE</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-20 text-center text-gray-300 font-bold italic text-sm">Belum ada data pengembalian.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MODAL --}}
        <div x-show="showModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" style="display: none;" x-transition>
            <div class="bg-white rounded-3xl w-full max-w-md overflow-hidden shadow-2xl" @click.away="showModal = false">
                <div class="bg-indigo-600 p-6 text-white">
                    <h3 class="font-bold text-lg">Input Verifikasi</h3>
                    <p class="text-[10px] opacity-70" x-text="selectedKode + ' - ' + selectedBarang"></p>
                </div>

                <form :action="'{{ url('admin/peminjaman/selesaikan') }}/' + selectedId" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 text-[10px]">
                        <div class="flex justify-between mb-1">
                            <span class="text-gray-400 font-bold">DENDA TERLAMBAT:</span>
                            <span class="font-bold text-red-500" x-text="'Rp ' + dendaTelat.toLocaleString()"></span>
                        </div>
                        <div class="flex justify-between border-t border-dashed pt-2 mt-2">
                            <span class="font-black text-gray-700">TOTAL TAGIHAN:</span>
                            <span class="font-black text-indigo-600 text-xs" x-text="'Rp ' + totalDenda.toLocaleString()"></span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-[9px] font-black text-gray-400 uppercase mb-1">Kondisi Barang</label>
                        <select name="kondisi_kembali" x-model="kondisi" class="w-full bg-gray-50 border-none rounded-xl text-sm p-2.5">
                            <option value="Baik">✅ Baik / Normal</option>
                            <option value="Rusak">⚠️ Ada Kerusakan</option>
                            <option value="Hilang">❌ Barang Hilang</option>
                        </select>
                    </div>

                    <div x-show="kondisi !== 'Baik'" x-transition>
                        <label class="block text-[9px] font-black text-gray-400 uppercase mb-1">Denda Kerusakan (Rp)</label>
                        <input type="number" name="denda_kerusakan" x-model="dendaKerusakan" class="w-full bg-gray-50 border-none rounded-xl text-sm p-2.5">
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="showModal = false" class="flex-1 py-3 bg-gray-100 text-gray-400 rounded-2xl font-bold text-[10px] uppercase">Batal</button>
                        <button type="submit" class="flex-1 py-3 bg-indigo-600 text-white rounded-2xl font-bold text-[10px] uppercase shadow-lg shadow-indigo-100">Simpan & Verif</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>