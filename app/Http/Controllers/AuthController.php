<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

/*
    Controller Autentikasi (Pusat Keamanan Login & Pendaftaran)
    Validasi identitas pengguna, menjaga keamanan password dan mengarahkan pengguna ke halamannya berdasarkan role
*/
class AuthController extends Controller
{
    /* =============
      FITUR LOGIN
    ============= */
    public function login() {
        // Jika pengguna sudah login, langsung arahkan ke halaman yang sesuai
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user()->role->name);
        }
        
        return view('auth.login'); // Memanggil file login.blade.php
    }

    public function authenticate(Request $request) {
        // Validasi input form: pastikan email dan password diisi
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        // Auth::attempt akan mengecek email dan password ke database secara otomatis
        if (Auth::attempt($credentials)) {
            // Jika berhasil, buat ulang session untuk mencegah serangan pencurian session (Session Fixation)
            $request->session()->regenerate();
            // Ambil role pengguna yang berhasil login, lalu arahkan ke halamannya
            $userRole = Auth::user()->role->name;
            return $this->redirectBasedOnRole($userRole);
        }
        // Jika email/password salah, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email'); // Mengembalikan ketikan email agar user tidak perlu mengetik ulang
    }

    /* ===============
      FITUR REGISTER
    =============== */
    public function register() {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user()->role->name);
        }
        
        return view('auth.register');
    }

    public function storeRegister(Request $request) {
        // Validasi data pendaftaran
        $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'unique:users,email'],
            // Aturan 'confirmed' memaksa input password harus sama dengan password_confirmation
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $customerRole = Role::where('name', 'customer')->first();

        // Pembuatan akun
        User::create([
            'role_id' => $customerRole->id_roles,
            'branch_id' => null,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    /* =============
      FITUR LOGOUT
    ============= */
    public function logout(Request $request) {
        // Keluarkan pengguna dari sistem
        Auth::logout();
        // Hancurkan data session (seperti keranjang belanja) agar tidak bocor ke orang lain
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    /* ============
      FUNGSI RUTE
    ============ */
    // Fungsi ini bertugas memilah rute berdasarkan jabatan
    private function redirectBasedOnRole($roleName) {
        if ($roleName === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($roleName === 'manager') {
            return redirect()->route('manager.dashboard');
        } else {
            return redirect()->route('main'); 
        }
    }
}