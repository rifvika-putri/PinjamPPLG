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
            return parseInt(this.dendaTelat) + (parseInt(this.dendaKerusakan) || 0);
        }
    }" class="space-y-6">
        
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-[#272b34]">Pengembalian Barang</h2>
                <p class="text-sm text-gray-400">Data audit lengkap peminjaman dan pengembalian</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-x-auto">
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
                            <div class="text-[10px] text-indigo-500 font-bold mt-1">
                                <i class="fas fa-phone text-[8px] mr-1"></i>{{ $p->user->no_telp ?? '-' }}
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="relative group">
                                    <img src="{{ asset('storage/barang/' . $p->barang->foto) }}" 
                                         class="w-12 h-12 object-cover rounded-xl border border-gray-100 shadow-sm transition group-hover:scale-105">
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900">{{ $p->barang->nama_barang }}</div>
                                    <div class="text-[10px] text-indigo-500 font-mono font-bold">{{ $p->barang->kode_barang }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                <div class="text-[9px] text-gray-400 font-bold">PINJAM: <span class="text-gray-700">{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/y') }}</span></div>
                                <div class="text-[9px] text-gray-400 font-bold">TARGET: <span class="text-gray-700">{{ \Carbon\Carbon::parse($p->tanggal_kembali_rencana)->format('d/m/y') }}</span></div>
                                <div class="text-[9px] text-gray-400 font-bold">REAL: <span class="text-gray-700">{{ $p->tanggal_kembali_realisasi ? \Carbon\Carbon::parse($p->tanggal_kembali_realisasi)->format('d/m/y, H:i') : '-' }}</span></div>
                                
                                @if($p->tanggal_kembali_realisasi)
                                    @php
                                        $terlambat = \Carbon\Carbon::parse($p->tanggal_kembali_realisasi)->gt(\Carbon\Carbon::parse($p->tanggal_kembali_rencana));
                                    @endphp
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded text-[8px] font-black uppercase {{ $terlambat ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                        {{ $terlambat ? 'Terlambat' : 'Tepat Waktu' }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="text-[10px] font-bold text-gray-700 mb-1">Kondisi: {{ $p->kondisi_kembali ?? 'Dicek Admin' }}</div>
                            <div class="text-[10px] text-gray-400 italic leading-tight max-w-[120px]">
                                {{ $p->catatan_kerusakan ?? 'Tidak ada catatan kerusakan' }}
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            @if($p->status == 'selesai')
                                <div class="text-[10px] space-y-0.5">
                                    <div class="flex justify-between text-red-500"><span>Telat:</span> <span>Rp{{ number_format($p->denda_telat) }}</span></div>
                                    <div class="flex justify-between text-orange-500 border-b border-gray-100 pb-1"><span>Rusak:</span> <span>Rp{{ number_format($p->denda_kerusakan) }}</span></div>
                                    <div class="flex justify-between font-black text-indigo-600 pt-0.5 uppercase"><span>Total:</span> <span>Rp{{ number_format($p->total_denda) }}</span></div>
                                </div>
                            @else
                                <span class="text-[10px] text-gray-300 italic">Menunggu Verifikasi</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($p->status == 'selesai')
                                <span class="{{ $p->status_pembayaran == 'Lunas' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter">
                                    {{ $p->status_pembayaran }}
                                </span>
                                <div class="text-[8px] text-gray-400 mt-1 font-bold uppercase">{{ $p->metode_pembayaran ?? '-' }}</div>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right">
                            @if($p->status == 'kembalikan pending')
                                <button @click="showModal = true; selectedId = '{{ $p->id }}'; selectedBarang = '{{ $p->barang->nama_barang }}'; selectedKode = '{{ $p->barang->kode_barang }}'; deadline = '{{ $p->tanggal_kembali_rencana }}'; hitungDenda()"
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-[10px] font-bold shadow-md shadow-indigo-100 transition-all active:scale-95">
                                    VERIFIKASI
                                </button>
                            @else
                                <div class="text-green-500 flex items-center justify-end gap-1 font-black text-[10px] uppercase tracking-widest">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    Done
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-24 text-center">
                            <div class="flex flex-col items-center opacity-20">
                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-sm font-bold italic">Belum ada pengembalian yang masuk.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div x-show="showModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" style="display: none;" x-transition>
            <div class="bg-white rounded-3xl w-full max-w-md overflow-hidden shadow-2xl" @click.away="showModal = false">
                <div class="bg-indigo-600 p-6 text-white relative">
                    <h3 class="font-bold text-lg leading-tight">Verifikasi Pengembalian</h3>
                    <p class="text-xs opacity-70 mt-1" x-text="selectedKode + ' - ' + selectedBarang"></p>
                </div>

                <form :action="'{{ url('admin/peminjaman/selesaikan') }}/' + selectedId" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="flex justify-between text-[10px] mb-1">
                            <span class="text-gray-500 font-bold uppercase tracking-widest">Denda Telat:</span>
                            <span class="font-bold text-red-500" x-text="'Rp ' + dendaTelat.toLocaleString()"></span>
                        </div>
                        <div class="flex justify-between text-[10px]">
                            <span class="text-gray-500 font-bold uppercase tracking-widest">Denda Rusak:</span>
                            <span class="font-bold text-orange-500" x-text="'Rp ' + (parseInt(dendaKerusakan) || 0).toLocaleString()"></span>
                        </div>
                        <div class="flex justify-between text-sm mt-3 pt-2 border-t border-dashed border-gray-300">
                            <span class="font-black text-gray-700 uppercase text-[11px]">Total Tagihan:</span>
                            <span class="font-black text-indigo-600" x-text="'Rp ' + totalDenda.toLocaleString()"></span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Kondisi Akhir Barang</label>
                        <select name="kondisi_kembali" x-model="kondisi" class="w-full bg-gray-50 border-gray-100 rounded-xl text-sm p-2.5 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                            <option value="Baik">✅ Kembali dengan Baik</option>
                            <option value="Rusak">⚠️ Barang Rusak</option>
                            <option value="Hilang">❌ Barang Hilang</option>
                        </select>
                    </div>

                    <div x-show="kondisi !== 'Baik'" x-transition class="space-y-3">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nominal Denda Kerusakan (Rp)</label>
                            <input type="number" name="denda_kerusakan" x-model="dendaKerusakan"
                                   class="w-full bg-gray-50 border-gray-100 rounded-xl text-sm p-2.5 outline-none focus:ring-2 focus:ring-red-500">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Catatan Detail Kerusakan</label>
                            <textarea name="catatan_kerusakan" rows="2" placeholder="Contoh: Layar retak di pojok kanan..."
                                      class="w-full bg-gray-50 border-gray-100 rounded-xl text-sm p-3 outline-none focus:ring-2 focus:ring-red-500"></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Status Bayar</label>
                            <select name="status_pembayaran" class="w-full bg-gray-50 border-gray-100 rounded-xl text-sm p-2.5 outline-none">
                                <option value="Lunas">Lunas</option>
                                <option value="Belum Lunas">Belum Lunas</option>
                            </select>
                        </div>
                        <div x-data="{ via: 'Tunai' }">
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Metode Pembayaran</label>
                        <select name="metode_pembayaran" x-model="via" class="w-full bg-gray-50 border-gray-100 rounded-xl text-sm p-2.5 outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="Tunai">💰 Tunai / Cash</option>
                            <option value="Transfer (BCA)">🏦 Transfer - BCA</option>
                            <option value="Transfer (Dana)">📱 Transfer - Dana</option>
                            <option value="Transfer (QRIS)">📸 QRIS</option>
                        </select>

                        <div x-show="via !== 'Tunai'" class="mt-3 p-3 bg-indigo-50 border border-indigo-100 rounded-2xl">
                            <p class="text-[10px] font-bold text-indigo-600 uppercase mb-1">Info Pembayaran:</p>
                            
                            <template x-if="via === 'Transfer (BCA)'">
                                <div class="text-xs text-gray-700">
                                    <p>No. Rek: <strong>123-456-7890</strong></p>
                                    <p>A/N: <strong>SMKN 1 CIOMAS</strong></p>
                                </div>
                            </template>

                            <template x-if="via === 'Transfer (DANA)'">
                                <div class="text-xs text-gray-700">
                                    <p>No. Rek: <strong>123-456-7890</strong></p>
                                    <p>A/N: <strong>PPLG SMKN 1 CIOMAS</strong></p>
                                </div>
                            </template>

                            <template x-if="via === 'Transfer (QRIS)'">
                                <div class="text-center">
                                    <img src="{{ asset('img/qris_sekolah.png') }}" class="w-32 h-32 mx-auto rounded-lg shadow-sm">
                                    <p class="text-[9px] text-gray-400 mt-1 italic font-bold">Silahkan Scan QRIS di atas</p>
                                </div>
                            </template>
                        </div>
                    </div>
                    </div>

                    <div class="flex gap-3 pt-4 border-t border-gray-50">
                        <button type="button" @click="showModal = false" class="flex-1 py-3.5 bg-gray-100 text-gray-500 rounded-2xl font-bold text-[10px] uppercase tracking-widest">Batal</button>
                        <button type="submit" class="flex-1 py-3.5 bg-indigo-600 text-white rounded-2xl font-bold text-[10px] uppercase tracking-widest shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all">Selesaikan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>