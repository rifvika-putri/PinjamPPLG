<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Analistik') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{}"> {{-- Menambahkan scope x-data agar dispatch jalan --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[3rem] p-10 border border-gray-100">
                
                <div class="flex justify-between items-center mb-10">
                    <div>
                        <h1 class="text-3xl font-black text-gray-800 tracking-tight">Data Statistik</h1>
                        <p class="text-gray-500 text-sm">Monitoring aset dan aktivitas sekolah secara real-time.</p>
                    </div>
                    <button @click="$dispatch('buka-modal-rekap-global')" type="button" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-[2rem] font-bold shadow-xl shadow-indigo-200 transition-all transform hover:scale-105 active:scale-95 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Buat Rekap
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                    <div class="bg-red-50 p-6 rounded-[2.5rem] border border-red-100 flex flex-col justify-between h-36">
                        <span class="text-[10px] font-black text-red-500 uppercase tracking-widest">Barang Rusak</span>
                        <h2 class="text-4xl font-black text-gray-800">{{ $rekap['barang_rusak'] }}</h2>
                    </div>

                    <div class="bg-indigo-50 p-6 rounded-[2.5rem] border border-indigo-100 flex flex-col justify-between h-36">
                        <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">Peminjaman</span>
                        <h2 class="text-4xl font-black text-gray-800">{{ $rekap['total_pinjam'] }}</h2>
                    </div>

                    <div class="bg-amber-50 p-6 rounded-[2.5rem] border border-amber-100 flex flex-col justify-between h-36">
                        <span class="text-[10px] font-black text-amber-600 uppercase tracking-widest">Siswa Denda</span>
                        <h2 class="text-4xl font-black text-gray-800">{{ $rekap['siswa_denda'] }}</h2>
                    </div>

                    <div class="bg-emerald-50 p-6 rounded-[2.5rem] border border-emerald-100 flex flex-col justify-between h-36">
                        <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Total Aset</span>
                        <h2 class="text-4xl font-black text-gray-800">{{ $rekap['total_barang'] }}</h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="bg-gray-50 p-8 rounded-[3rem] border border-gray-100">
                        <h3 class="font-bold text-gray-700 mb-6 flex items-center gap-2 text-sm uppercase tracking-wider">
                            <span class="w-2 h-2 bg-indigo-500 rounded-full"></span>
                            Tingkat Peminjaman Bulanan
                        </h3>
                        <canvas id="chartPinjam" height="180"></canvas>
                    </div>

                    <div class="bg-gray-50 p-8 rounded-[3rem] border border-gray-100">
                        <h3 class="font-bold text-gray-700 mb-6 flex items-center gap-2 text-sm uppercase tracking-wider">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            Statistik Kerusakan Barang
                        </h3>
                        <canvas id="chartRusak" height="180"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-data="{ open: false }" 
         @buka-modal-rekap-global.window="open = true" 
         x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100"
         class="fixed inset-0 z-[9999] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4" 
         style="display: none;">
        
        <div @click.away="open = false" class="bg-white rounded-[3rem] w-full max-w-md overflow-hidden shadow-2xl">
            <div class="bg-indigo-600 p-8 text-white text-center">
                <h3 class="font-bold text-xl font-sans">Download Laporan</h3>
                <p class="text-xs opacity-70 mt-1">Pilih kriteria data yang ingin direkap</p>
            </div>
            
            <div class="p-8 space-y-5">
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Kategori Laporan</label>
                    <select id="global_jenisRekap" onchange="togglePeriodeGlobal()" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 mt-2 focus:ring-2 focus:ring-indigo-500 text-sm">
                        <option value="petugas">Daftar Petugas</option>
                        <option value="kategori">Daftar Kategori</option>
                        <option value="barang">Semua Data Barang</option>
                        <option value="barang_rusak">Data Barang Rusak</option>
                        <option value="peminjaman">Data Peminjaman</option>
                        <option value="pengembalian">Data Pengembalian</option>
                    </select>
                </div>

                <div id="global_detailPeriode" class="hidden animate-fade-in">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Pilih Periode</label>
                    <select id="global_periode" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 mt-2 focus:ring-2 focus:ring-indigo-500 text-sm">
                        <option value="hari">Hari Ini</option>
                        <option value="minggu">Minggu Ini</option>
                        <option value="bulan">Bulan Ini</option>
                        <option value="tahun">Tahun Ini</option>
                    </select>
                </div>

                <div class="flex gap-4 pt-4">
                    <button @click="open = false" type="button" class="flex-1 py-4 bg-gray-100 text-gray-500 rounded-2xl font-bold uppercase text-[10px] tracking-widest">Batal</button>
                    <button onclick="eksekusiCetakGlobal()" type="button" class="flex-1 py-4 bg-indigo-600 text-white rounded-2xl font-bold uppercase text-[10px] tracking-widest shadow-lg shadow-indigo-100">Download PDF</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = {!! json_encode($labels) !!};

        // 1. Grafik Peminjaman (Batang Modern)
        new Chart(document.getElementById('chartPinjam'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Peminjaman',
                    data: {!! json_encode($dataPeminjaman) !!},
                    backgroundColor: '#6366f1',
                    borderRadius: 10,
                    barThickness: 15
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { 
                    y: { beginAtZero: true, grid: { display: false } },
                    x: { grid: { display: false } }
                }
            }
        });

        // 2. Grafik Kerusakan (Batang Merah)
        new Chart(document.getElementById('chartRusak'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Rusak',
                    data: {!! json_encode($dataKerusakan) !!},
                    backgroundColor: '#f43f5e',
                    borderRadius: 10,
                    barThickness: 15
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { 
                    y: { beginAtZero: true, grid: { display: false } },
                    x: { grid: { display: false } }
                }
            }
        });

        function togglePeriodeGlobal() {
            const jenis = document.getElementById('global_jenisRekap').value;
            const detail = document.getElementById('global_detailPeriode');
            if (['barang_rusak', 'peminjaman', 'pengembalian'].includes(jenis)) {
                detail.classList.remove('hidden');
            } else {
                detail.classList.add('hidden');
            }
        }

        function eksekusiCetakGlobal() {
            const jenis = document.getElementById('global_jenisRekap').value;
            const periode = document.getElementById('global_periode').value;
            window.location.href = `/admin/laporan/cetak?jenis=${jenis}&periode=${periode}`;
        }
    </script>
    @endpush
</x-app-layout>