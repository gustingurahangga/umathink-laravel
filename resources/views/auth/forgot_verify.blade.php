<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UmaThink - Belajar &amp; Bermain</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/show.css') }}">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
</head>

<body>
    <div class="register-container">

        <!-- BAGIAN KIRI -->
        <div class="left-section">
            <div class="text-content">
                <h2>Cek Email Anda</h2>
                <p>Masukan Kode OTP yang telah kami kirimkan ke email Anda untuk mereset password.</p>
            </div>
            <img src="{{ asset('assets/img/show.png') }}" alt="UmaThink Mascot" class="mascot">
        </div>

        <!-- BAGIAN KANAN -->
        <div class="right-section">

            @if(session('success'))
            <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; font-size: 14px; border: 1px solid #c3e6cb;">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; font-size: 14px; border: 1px solid #f5c6cb;">
                {{ session('error') }}
            </div>
            @endif

            <form action="{{ route('forgot.verify.submit', $unique_id) }}" method="POST" class="register-form">
                @csrf

                <div class="input-group">
                    <i class="icon">🔗</i>
                    <input type="number" name="otp" placeholder="Masukan Kode OTP Disini" required>
                </div>

                <button type="submit" class="btn-register">Verifikasi Kode</button>

                <!-- Tombol kembali ke login -->
                <p class="login-link" style="margin-top: 15px;">
                    <a href="{{ route('login') }}">Kembali ke Login</a>
                </p>
            </form>

        </div>
    </div>
</body>

</html>
