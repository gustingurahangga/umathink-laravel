<?php

namespace App\Http\Controllers;

use App\Mail\OtpEmail;
use App\Models\User;
use App\Models\Verification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;

class ForgotPasswordController extends Controller
{
    // 1. Tampilkan form input email
    public function showEmailForm()
    {
        return view('auth.forgot_password');
    }

    // 2. Proses pengiriman OTP
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'Alamat email ini tidak terdaftar di sistem kami.'
        ]);

        $user = User::where('email', $request->email)->first();

        // Invalidate OTP sebelumnya yang masih aktif untuk user ini
        Verification::where('user_id', $user->id)
            ->where('type', 'reset_password')
            ->update(['status' => 'invalid']);

        $otp = rand(100000, 999999);
        $unique_id = Str::random(40);

        Verification::create([
            'user_id'   => $user->id,
            'unique_id' => $unique_id,
            'otp'       => md5($otp),
            'type'      => 'reset_password',
            'send_via'  => 'email',
            'status'    => 'active'
        ]);

        Mail::to($user->email)->send(new OtpEmail($otp));

        return redirect()->route('forgot.verify.form', ['unique_id' => $unique_id])
            ->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    // 3. Tampilkan form verifikasi OTP
    public function showVerifyForm($unique_id)
    {
        $verify = Verification::where('unique_id', $unique_id)
            ->where('type', 'reset_password')
            ->where('status', 'active')
            ->first();

        // Cek kadaluarsa
        if ($verify && $verify->created_at->diffInMinutes(now()) > 15) {
            $verify->update(['status' => 'invalid']);
            $verify = null;
        }

        if (!$verify) {
            return redirect()->route('forgot.email.form')->with('error', 'Sesi OTP tidak valid atau sudah kadaluarsa (lebih dari 15 menit).');
        }

        return view('auth.forgot_verify', compact('unique_id', 'verify'));
    }

    // 4. Proses pengecekan OTP
    public function checkOtp(Request $request, $unique_id)
    {
        $request->validate([
            'otp' => 'required|numeric'
        ]);

        $verify = Verification::where('unique_id', $unique_id)
            ->where('type', 'reset_password')
            ->where('status', 'active')
            ->first();

        if (!$verify) {
            return redirect()->route('forgot.email.form')->with('error', 'Sesi OTP tidak valid.');
        }

        // Cek Kadaluarsa
        if ($verify->created_at->diffInMinutes(now()) > 15) {
            $verify->update(['status' => 'invalid']);
            return redirect()->route('forgot.email.form')->with('error', 'Kode OTP sudah kadaluarsa (lebih dari 15 menit). Silakan minta kode baru.');
        }

        $rateLimitKey = 'forgot-otp-'.$unique_id;

        // Rate Limiter: Maksimal 3 kali percobaan
        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            $verify->update(['status' => 'invalid']); // Hanguskan
            RateLimiter::clear($rateLimitKey);
            return redirect()->route('forgot.email.form')->with('error', 'Anda salah memasukkan OTP 3 kali. Kode dihanguskan, silakan minta kode baru.');
        }

        if (md5($request->otp) !== $verify->otp) {
            RateLimiter::hit($rateLimitKey, 600);
            $attemptsLeft = 3 - RateLimiter::attempts($rateLimitKey);
            return back()->with('error', "Kode OTP salah! Sisa percobaan: {$attemptsLeft}");
        }

        RateLimiter::clear($rateLimitKey);

        // Jika benar, ubah status ke 'valid' agar bisa lanjut reset
        $verify->update(['status' => 'valid']);

        return redirect()->route('forgot.reset.form', ['unique_id' => $unique_id]);
    }

    // 5. Tampilkan form reset password
    public function showResetForm($unique_id)
    {
        $verify = Verification::where('unique_id', $unique_id)
            ->where('type', 'reset_password')
            ->where('status', 'valid')
            ->first();

        if ($verify && $verify->updated_at->diffInMinutes(now()) > 30) {
            // Sesi ganti password hangus setelah 30 menit dari verifikasi sukses
            $verify->update(['status' => 'invalid']);
            $verify = null;
        }

        if (!$verify) {
            return redirect()->route('forgot.email.form')->with('error', 'Sesi reset password tidak valid atau sudah berakhir (timeout 30 menit).');
        }

        return view('auth.reset_password', compact('unique_id'));
    }

    // 6. Proses update password baru
    public function updatePassword(Request $request, $unique_id)
    {
        $request->validate([
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|min:8|same:password'
        ], [
            'confirm_password.same' => 'Konfirmasi password tidak cocok.'
        ]);

        $verify = Verification::where('unique_id', $unique_id)
            ->where('type', 'reset_password')
            ->where('status', 'valid')
            ->first();

        if (!$verify) {
            return redirect()->route('forgot.email.form')->with('error', 'Gagal mereset password. Sesi tidak valid.');
        }

        $user = User::find($verify->user_id);
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Tandai verifikasi sudah digunakan
        $verify->update(['status' => 'invalid']);

        return redirect('/login')->with('success', 'Password berhasil diubah! Silakan login dengan password baru.');
    }
}
