<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // validasi input
        $credentials = $request->validate([
            'username' => 'required|string|max:50',
            'password' => 'required|string|max:50',
        ]);

        // proses login
        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate();
            if (Auth::user()->role == 'customer') return redirect('/customer');
            return redirect('/admin/dashboard');
        }

        // jika login gagal
        return back()->withErrors([
            'username' => 'Username atau password salah, silahkan coba lagi'
        ])->withInput();
    }

    function register(Request $request)
    {
        $request->validate([
            'username'          =>  'required|string|max:50',
            'namalengkap'       =>  'required|string|max:50',
            'email'             =>  'required|string|max:50',
            'password'          =>  'required|string|max:50|min:8|',
            'confirm_password'  =>  'required|string|max:50|min:8|same:password',
        ]);

        $request['status'] = "verify";
        $user = User::create($request->all());
        Auth::login($user, true);
        return Redirect('/verify');
    }

    public function google_redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function google_callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors([
                'username' => 'Login dengan Google gagal. Silahkan coba lagi.',
            ]);
        }

        // Cari user berdasarkan google_id atau email
        $user = User::where('google_id', $googleUser->id)
            ->orWhere('email', $googleUser->email)
            ->first();

        if ($user) {
            // Update google_id dan avatar jika belum ada
            if (!$user->google_id) {
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                ]);
            }
        } else {
            // Buat user baru
            $user = User::create([
                'username' => 'user_' . uniqid(),
                'namalengkap' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
                'password' => Hash::make(Str::random(16)),
                'status' => 'active',
                'role' => 'customer',
            ]);
        }

        if ($user->status == 'banned') {
            return redirect('/login')->withErrors([
                'username' => 'Akun anda telah di-banned.',
            ]);
        }

        if ($user->status == 'verify') {
            $user->update(['status' => 'active']);
        }

        Auth::login($user, true);

        if ($user->role == 'customer') return redirect('/customer');
        return redirect('/admin');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
