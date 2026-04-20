<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-blue-600 rounded-2xl shadow-lg shadow-blue-200 mb-4">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
        </div>
        <h2 class="text-2xl font-black text-slate-800 tracking-tight">Daftar Akun Baru</h2>
        <p class="text-sm text-slate-500 mt-1">Lengkapi data dirimu untuk mulai meminjam alat.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 ml-1">Nama Lengkap</label>
                <input id="name" class="block w-full px-4 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-600 transition-all text-sm text-slate-700" type="text" name="name" :value="old('name')" required autofocus placeholder="Nama Anda" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <div>
                <label for="nisn" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 ml-1">NISN / NIS</label>
                <input id="nisn" class="block w-full px-4 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-600 transition-all text-sm text-slate-700" type="text" name="nisn" :value="old('nisn')" required placeholder="Nomor Induk" />
                <x-input-error :messages="$errors->get('nisn')" class="mt-1" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="email" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 ml-1">Email</label>
                <input id="email" class="block w-full px-4 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-600 transition-all text-sm text-slate-700" type="email" name="email" :value="old('email')" required placeholder="nama@email.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <div>
                <label for="no_telp" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 ml-1">No. WhatsApp</label>
                <input id="no_telp" class="block w-full px-4 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-600 transition-all text-sm text-slate-700" type="text" name="no_telp" :value="old('no_telp')" required placeholder="0812..." />
                <x-input-error :messages="$errors->get('no_telp')" class="mt-1" />
            </div>
        </div>

        <div>
            <label for="kelas" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 ml-1">Kelas</label>
            <input id="kelas" class="block w-full px-4 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-600 transition-all text-sm text-slate-700" type="text" name="kelas" :value="old('kelas')" required placeholder="Contoh: XII PPLG 1" />
            <x-input-error :messages="$errors->get('kelas')" class="mt-1" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-slate-100 pt-4">
            <div>
                <label for="password" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 ml-1">Kata Sandi</label>
                <input id="password" class="block w-full px-4 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-600 transition-all text-sm text-slate-700" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 ml-1">Konfirmasi</label>
                <input id="password_confirmation" class="block w-full px-4 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-600 transition-all text-sm text-slate-700" type="password" name="password_confirmation" required placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full bg-slate-900 text-white py-3.5 rounded-2xl font-bold shadow-xl hover:bg-slate-800 active:scale-[0.98] transition-all flex justify-center items-center gap-2 text-sm mb-4">
                {{ __('Daftar Sekarang') }}
                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </button>
            
            <p class="text-center text-xs font-medium text-slate-500">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline italic">Masuk di sini</a>
            </p>
        </div>
    </form>
</x-guest-layout>