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

            <h2>Buat Password Baru</h2>
            <p style="margin-bottom: 20px; color: #666; font-size: 14px;">Silakan buat password baru Anda. Pastikan untuk mengingatnya.</p>

            @error('password')
            <div class="popup-error" style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; font-size: 14px; border: 1px solid #f5c6cb;">
                {{ $message }}
            </div>
            @enderror

            @error('confirm_password')
            <div class="popup-error" style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; font-size: 14px; border: 1px solid #f5c6cb;">
                {{ $message }}
            </div>
            @enderror

            <form action="{{ route('forgot.reset.submit', $unique_id) }}" method="POST">
                @csrf
                <div class="input-group">
                    <label><i class="icon">🔒</i></label>
                    <input type="password" name="password" placeholder="Password Baru (Min. 8 karakter)" required>
                </div>

                <div class="input-group">
                    <label><i class="icon">🔒</i></label>
                    <input type="password" name="confirm_password" placeholder="Konfirmasi Password Baru" required>
                </div>

                <button type="submit" class="btn-login" style="margin-top: 15px;">Simpan Password Baru</button>
            </form>
        </div>
    </div>

    <script src="{{ asset('assets/js/script.js') }}"></script>
</body>

</html>
