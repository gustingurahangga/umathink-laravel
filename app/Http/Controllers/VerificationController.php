<?php

namespace App\Http\Controllers;

use App\Mail\OtpEmail;
use App\Models\User;
use App\Models\Verification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;


class VerificationController extends Controller
{
    public function index()
    {
        return view('verification.index');
    }

    public function show(Request $request, $unique_id)
    {
        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        $verify = Verification::where('user_id', $user->id)
            ->where('unique_id', $unique_id)
            ->where('status', 'active')
            ->first();

        // Cek Kadaluarsa (15 menit)
        if ($verify && $verify->created_at->diffInMinutes(now()) > 15) {
            $verify->update(['status' => 'invalid']);
            $verify = null; // Dianggap tidak ada agar tidak bisa diakses
        }

        return view('verification.show', compact('unique_id', 'verify'));
    }


    public function update(Request $request, $unique_id)
    {
        $user = $request->user(); // ini standar Laravel

        if (!$user) {
            abort(403);
        }

        $verify = Verification::where('user_id', $user->id)
            ->where('unique_id', $unique_id)
            ->where('status', 'active')
            ->first();

        if (!$verify) {
            abort(404);
        }

        // Cek Kadaluarsa (15 menit)
        if ($verify->created_at->diffInMinutes(now()) > 15) {
            $verify->update(['status' => 'invalid']);
            return redirect('/verify')->with('failed', 'Kode OTP sudah kadaluarsa (lebih dari 15 menit). Silakan minta kode baru.');
        }

        $rateLimitKey = 'verify-otp-'.$unique_id;
        
        // Rate Limiter: Maksimal 3 kali percobaan salah
        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            $verify->update(['status' => 'invalid']); // Hanguskan OTP setelah 3x salah
            RateLimiter::clear($rateLimitKey);
            return redirect('/verify')->with('failed', 'Anda telah salah memasukkan OTP sebanyak 3 kali. Kode OTP dihanguskan, silakan minta kode baru.');
        }

        if (md5($request->otp) !== $verify->otp) {
            RateLimiter::hit($rateLimitKey, 600); // 10 menit penalty log
            $attemptsLeft = 3 - RateLimiter::attempts($rateLimitKey);
            return back()->with('error', "Kode OTP salah! Sisa percobaan: {$attemptsLeft}");
        }

        RateLimiter::clear($rateLimitKey);

        $verify->update(['status' => 'valid']);
        $user->update(['status' => 'active']);

        return redirect('/customer');
    }

    public function store(Request $request)
    {
        if ($request->type == 'register') {
            $user = User::find($request->user()->id);
        } else {
            // reset password logic di sini
            $user = $request->user();
        }

        if (!$user) {
            return back()->with('failed', 'User Not Found.');
        }

        $otp = rand(100000, 999999);

        $verify = Verification::create([
            'user_id'   => $user->id,
            'unique_id' => Str::random(40), // Menggunakan string acak yang jauh lebih aman dari uniqid()
            'otp'       => md5($otp),
            'type'      => $request->type,
            'send_via'  => 'email',
            'status'    => 'active'
        ]);

        Mail::to($user->email)->send(new OtpEmail($otp));

        if ($request->type == 'register') {
            return redirect('/verify/' . $verify->unique_id);
        }

        // contoh redirect reset password
        // return redirect('/reset-password/' . $verify->unique_id);
    }
}
