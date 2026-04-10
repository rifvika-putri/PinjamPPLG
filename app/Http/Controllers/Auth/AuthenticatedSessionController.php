<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pastikan ini ada
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Ambil data user yang baru saja login
        $user = Auth::user();

        // Gunakan redirect()->route() secara langsung (tanpa intended) 
        // agar tidak dipaksa balik ke halaman admin jika sedang login sebagai siswa
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } 
        
        if ($user->role === 'petugas') {
            return redirect()->route('petugas.dashboard');
        }

        // Default untuk siswa
        return redirect()->route('dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}