<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'nisn' => ['required', 'string', 'max:20', 'unique:'.User::class], // Validasi NISN
        'kelas' => ['required', 'string', 'max:50'], // Validasi Kelas
        'no_telp' => ['required', 'string', 'max:15'], // Validasi no telp
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'nisn' => $request->nisn,   // Simpan NISN
        'kelas' => $request->kelas, // Simpan Kelas
        'no_telp' => $request->no_telp, // Simpan ke database
        'password' => Hash::make($request->password),
        'role' => 'siswa',          // Set otomatis sebagai siswa
    ]);

    event(new Registered($user));

    Auth::login($user);

    return redirect(route('dashboard', absolute: false));
}
}