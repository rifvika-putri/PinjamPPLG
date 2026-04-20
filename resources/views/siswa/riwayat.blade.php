<x-app-layout>
    <div x-data="{ 
        openModal: false, 
        openModalBayar: false,
        selectedId: '', 
        selectedNama: '',
        selectedKode: '',
        selectedDenda: 0,
        metode: ''
    }" class="p-6">
        
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-[#272b34]">Riwayat Peminjaman Saya</h2>
            <p class="text-sm text-gray-400">Pantau status pengembalian dan denda barang kamu di sini</p>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-[10px] uppercase font-extrabold tracking-widest text-gray-400">
                    <tr>
                        <th class="px-6 py-4">Barang</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4">Waktu Pinjam/Kembali</th>
                        <th class="px-6 py-4">Catatan Kondisi</th>
                        <th class="px-6 py-4 text-right">Denda & Pembayaran</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($peminjamans as $p)
                    <tr class="text-sm hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">{{ $p->barang->nama_barang }}</div>
                            <div class="text-[10px] text-indigo-500 font-mono">{{ $p->barang->kode_barang }}</div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-full text-[9px] font-extrabold uppercase tracking-tighter
                                {{ $p->status == 'pending' ? 'bg-amber-100 text-amber-600' : 
                                   ($p->status == 'dipinjam' ? 'bg-blue-100 text-blue-600' : 
                                   ($p->status == 'kembalikan_pending' ? 'bg-purple-100 text-purple-600' : 'bg-emerald-100 text-emerald-600')) }}">
                                {{ $p->status }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-xs">
                            <div class="text-gray-600 font-bold">{{ $p->tanggal_pinjam->format('d M Y') }}</div>
                            @if($p->tanggal_kembali_realisasi)
                                <div class="text-[10px] text-emerald-500 font-medium italic">Kembali: {{ \Carbon\Carbon::parse($p->tanggal_kembali_realisasi)->format('H:i') }} WIB</div>
                            @else
                                <div class="text-[10px] text-gray-400">Batas: {{ \Carbon\Carbon::parse($p->tanggal_kembali_rencana)->format('H:i') }} WIB</div>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-[11px] text-gray-500">
                            {{ $p->catatan_kondisi ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-right">
                        @if($p->total_denda > 0)
                            <div class="text-sm font-black text-red-600">Rp {{ number_format($p->total_denda, 0, ',', '.') }}</div>
                            <span class="inline-block mt-1 px-2 py-0.5 rounded text-[8px] font-bold uppercase 
                                {{ $p->status_pembayaran == 'Lunas' ? 'bg-green-100 text-green-600' : 'bg-red-50 text-red-400' }}">
                                {{ $p->status_pembayaran ?? 'Belum Bayar' }}
                            </span>
                        @else
                            <span class="text-gray-300 italic text-[10px]">Tidak ada denda</span>
                        @endif
                    </td>

                      <td class="px-6 py-4 text-right">
                        <div class="flex flex-col items-end gap-2">

                            {{-- 1. STATUS: PENDING (Baru ajukan, belum disetujui admin) --}}
                            @if($p->status == 'pending')
                                <form action="{{ route('peminjaman.batal', $p->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 text-[10px] font-bold uppercase hover:underline">Batalkan</button>
                                </form>

                            {{-- 2. STATUS: DIPINJAM (Barang ada di siswa) --}}
                            @elseif($p->status == 'dipinjam')
                                <button @click="openModal = true; selectedId = '{{ $p->id }}'; selectedNama = '{{ $p->barang->nama_barang }}'" 
                                    class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-[10px] font-bold shadow-md">
                                    KEMBALIKAN
                                </button>

                            {{-- 3. STATUS: KEMBALIKAN PENDING (Barang sudah dibalikin, tapi admin belum verifikasi atau ada denda) --}}
                            @elseif($p->status == 'kembalikan pending')
                                
                                {{-- CEK: Jika admin sudah isi denda tapi siswa belum bayar --}}
                                @if($p->total_denda > 0 && $p->status_pembayaran != 'PENDING' && $p->status_pembayaran != 'Lunas')
                                    <button @click="openModalBayar = true; selectedId = '{{ $p->id }}'; selectedDenda = '{{ number_format($p->total_denda, 0, ',', '.') }}'" 
                                        class="bg-red-600 text-white px-4 py-2 rounded-xl text-[10px] font-bold shadow-md animate-pulse">
                                        BAYAR DENDA
                                    </button>
                                
                                {{-- CEK: Jika siswa sudah upload bukti bayar denda (Menunggu verifikasi akhir) --}}
                                @elseif($p->status_pembayaran == 'PENDING')
                                    <span class="px-3 py-1 bg-amber-100 text-amber-600 rounded-lg text-[9px] font-bold uppercase">
                                        Verifikasi Pembayaran
                                    </span>

                                {{-- JIKA: Barang baru dibalikin dan admin belum verifikasi apa-apa --}}
                                @else
                                    <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-lg text-[9px] font-bold uppercase">
                                        Dicek Admin
                                    </span>
                                @endif

                            {{-- 4. STATUS: SELESAI --}}
                            @elseif($p->status == 'selesai')
                                <span class="text-emerald-500 font-black text-[10px] uppercase tracking-widest">✔ SELESAI</span>
                            @endif

                        </div>
                    </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-20 text-center text-gray-400 italic">Belum ada riwayat peminjaman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MODAL FORM PENGEMBALIAN (BAWAAN) --}}
        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" x-transition style="display: none;">
            <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl overflow-hidden" @click.away="openModal = false">
                <div class="bg-indigo-600 p-6 text-white text-center">
                    <h3 class="font-bold text-lg leading-tight uppercase tracking-widest text-[14px]">Form Pengembalian</h3>
                </div>
                <form action="{{ route('peminjaman.kembalikan') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    <input type="hidden" name="peminjaman_id" :value="selectedId">
                    <div class="bg-indigo-50/50 p-4 rounded-2xl mb-5 border border-indigo-100">
                        <p class="text-[9px] font-extrabold text-indigo-400 uppercase tracking-widest">Barang dikembalikan:</p>
                        <h4 class="font-black text-indigo-900" x-text="selectedNama"></h4>
                    </div>
                    <div class="mb-4">
                        <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2">Kondisi Barang Saat Ini</label>
                        <select name="kondisi_kembali" required class="w-full bg-gray-50 border-gray-100 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                            <option value="Baik">✅ Barang dalam Kondisi Baik</option>
                            <option value="Rusak">⚠️ Barang Rusak / Bermasalah</option>
                        </select>
                    </div>
                    <div class="mb-8">
                        <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2">Foto Bukti Fisik</label>
                        <input type="file" name="foto_kembali" required class="text-[10px] text-gray-500 w-full cursor-pointer transition-all">
                    </div>
                    <div class="flex gap-3">
                        <button type="button" @click="openModal = false" class="flex-1 py-4 bg-gray-50 text-gray-400 rounded-2xl font-black text-[10px] uppercase">BATAL</button>
                        <button type="submit" class="flex-1 py-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase shadow-lg shadow-indigo-200">KIRIM BUKTI</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL BAYAR DENDA (FITUR BARU) --}}
        <div x-show="openModalBayar" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" x-transition style="display: none;">
            <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl overflow-hidden" @click.away="openModalBayar = false">
                <div class="bg-red-600 p-6 text-white text-center">
                    <h3 class="font-bold text-lg leading-tight uppercase tracking-widest text-[14px]">Pembayaran Denda</h3>
                    <p class="text-[10px] opacity-70 mt-1">Total: Rp <span x-text="selectedDenda"></span></p>
                </div>
                <form action="{{ route('siswa.peminjaman.bayar') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="peminjaman_id" :value="selectedId">
                    
                    <div>
                        <label class="block text-[10px] font-extrabold text-gray-400 uppercase mb-2">Pilih Metode Pembayaran</label>
                        <select x-model="metode" name="metode" required class="w-full bg-gray-50 border-gray-100 rounded-2xl text-sm font-bold text-gray-700">
                            <option value="">-- Pilih --</option>
                            <option value="QRIS">QRIS</option>
                            <option value="TRANSFER">Transfer Bank</option>
                        </select>
                    </div>

                    <div x-show="metode === 'QRIS'" class="p-4 bg-gray-50 rounded-2xl text-center">
                        <p class="text-[9px] font-bold text-gray-400 mb-2">SILAKAN SCAN DISINI</p>
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=PEMBAYARAN_DENDA" class="mx-auto w-32 h-32 rounded-lg shadow-sm">
                    </div>

                    <div x-show="metode === 'TRANSFER'" class="p-4 bg-indigo-50 rounded-2xl">
                        <p class="text-xs font-bold text-indigo-700">BCA: 1234-567-890</p>
                        <p class="text-[10px] text-indigo-400">A/N INVENTARIS SEKOLAH</p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-extrabold text-gray-400 uppercase mb-2">Upload Bukti Bayar</label>
                        <input type="file" name="bukti_bayar" required class="text-[10px] text-gray-500 w-full cursor-pointer">
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="openModalBayar = false" class="flex-1 py-4 bg-gray-50 text-gray-400 rounded-2xl font-black text-[10px] uppercase">BATAL</button>
                        <button type="submit" class="flex-1 py-4 bg-red-600 text-white rounded-2xl font-black text-[10px] uppercase shadow-lg shadow-red-200 hover:bg-red-700">KONFIRMASI</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>