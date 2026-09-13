<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UmaThink - Belajar &amp; Bermain</title>

    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo-section">
                <img src="{{ asset('assets/img/umathink-logo.png') }}" alt="UmaThink Logo" class="logo">
                <h1><span class="uma">UMA</span><span class="think">THINK</span></h1>
            </div>

            <h2>Lupa Password?</h2>
            <p style="margin-bottom: 20px; color: #666; font-size: 14px;">Masukkan alamat email Anda yang terdaftar. Kami akan mengirimkan kode OTP untuk mereset password Anda.</p>

            @if(session('error'))
            <div id="errorPopup" class="popup-error" style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; font-size: 14px; border: 1px solid #f5c6cb;">
                {{ session('error') }}
            </div>
            @endif
            
            @error('email')
            <div class="popup-error" style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; font-size: 14px; border: 1px solid #f5c6cb;">
                {{ $message }}
            </div>
            @enderror

            <form action="{{ route('forgot.email.submit') }}" method="POST">
                @csrf
                <div class="input-group">
                    <label><i class="icon">✉️</i></label>
                    <input type="email" name="email" placeholder="Alamat Email" required>
                </div>

                <div class="links" style="justify-content: center; margin-top: -5px; margin-bottom: 20px;">
                    <a href="{{ route('login') }}" style="color: #2b4c7e; font-weight: 600;">Kembali ke halaman Login</a>
                </div>

                <button type="submit" class="btn-login" style="margin-top: 0;">Kirim Kode OTP</button>
            </form>
        </div>
    </div>

    <script src="{{ asset('assets/js/script.js') }}"></script>
</body>

</html>
