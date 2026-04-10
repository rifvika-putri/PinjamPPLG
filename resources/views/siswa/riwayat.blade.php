<x-app-layout>
    <div x-data="{ 
        openModal: false, 
        selectedId: '', 
        selectedNama: '',
        selectedKode: '' 
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
                        <th class="px-6 py-4 text-center">Status Peminjaman</th>
                        <th class="px-6 py-4">Waktu Pinjam/Kembali</th>
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
                                   ($p->status == 'kembalikan pending' ? 'bg-purple-100 text-purple-600' : 'bg-emerald-100 text-emerald-600')) }}">
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

                        <td class="px-6 py-4 text-right">
                            @if($p->total_denda > 0)
                                <div class="text-sm font-black text-red-600">Rp {{ number_format($p->total_denda, 0, ',', '.') }}</div>
                                <div class="text-[9px] text-gray-400 leading-tight">
                                    Telat: {{ number_format($p->denda_telat, 0, ',', '.') }}<br>
                                    Rusak: {{ number_format($p->denda_kerusakan, 0, ',', '.') }}
                                </div>
                                <span class="inline-block mt-1 px-2 py-0.5 rounded text-[8px] font-bold uppercase {{ $p->status_pembayaran == 'Lunas' ? 'bg-green-100 text-green-600' : 'bg-red-50 text-red-400' }}">
                                    {{ $p->status_pembayaran }}
                                </span>
                            @else
                                <span class="text-gray-300 italic text-[10px]">Tidak ada denda</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right">
                            @if($p->status == 'pending')
                                <form action="{{ route('peminjaman.batal', $p->id) }}" method="POST" onsubmit="return confirm('Yakin batal?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 text-[10px] font-extrabold uppercase hover:underline">Batalkan</button>
                                </form>
                            @elseif($p->status == 'dipinjam')
                                <button @click="openModal = true; selectedId = '{{ $p->id }}'; selectedNama = '{{ $p->barang->nama_barang }}'; selectedKode = '{{ $p->barang->kode_barang }}'" 
                                    class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-[10px] font-bold shadow-md shadow-indigo-100 hover:bg-indigo-700 transition">
                                    KEMBALIKAN
                                </button>
                            @else
                                <div class="flex flex-col items-end">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i>
                                    <span class="text-[9px] text-gray-400 mt-1 uppercase font-bold">Terverifikasi</span>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-20 text-center text-gray-400 italic">Belum ada riwayat peminjaman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MODAL FORM PENGEMBALIAN --}}
        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" x-transition style="display: none;">
            <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl overflow-hidden" @click.away="openModal = false">
                <div class="bg-indigo-600 p-6 text-white text-center">
                    <h3 class="font-bold text-lg leading-tight uppercase tracking-widest text-[14px]">Form Pengembalian</h3>
                    <p class="text-[10px] opacity-70 mt-1">Pastikan barang dalam kondisi yang sesuai sebelum dikirim</p>
                </div>
                
                <form action="{{ route('peminjaman.kembalikan') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="peminjaman_id" value="{{ $p->id }}">

                    <div class="bg-indigo-50/50 p-4 rounded-2xl mb-5 border border-indigo-100">
                        <p class="text-[9px] font-extrabold text-indigo-400 uppercase tracking-widest">Barang dikembalikan:</p>
                        <h4 class="font-black text-indigo-900" x-text="selectedNama"></h4>
                    </div>

                    <div class="mb-4 text-left">
                        <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2">Kondisi Barang Saat Ini</label>
                        <select name="kondisi_siswa" required 
                            class="w-full bg-gray-50 border-gray-100 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                            <option value="Baik">✅ Barang dalam Kondisi Baik</option>
                            <option value="Rusak">⚠️ Barang Rusak / Bermasalah</option>
                        </select>
                    </div>

                    <div class="mb-8 text-left">
                        <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2">Foto Bukti Fisik</label>
                        <div class="relative group">
                            <input type="file" name="foto_kembali" required 
                                class="text-[10px] text-gray-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-2xl file:border-0 file:text-[10px] file:font-black file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer w-full transition-all">
                        </div>
                        <p class="text-[9px] text-gray-400 mt-2 italic">*Upload foto barang asli saat ini</p>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" @click="openModal = false" class="flex-1 py-4 bg-gray-50 text-gray-400 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-gray-100 transition">BATAL</button>
                        <button type="submit" class="flex-1 py-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition">KIRIM BUKTI</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>