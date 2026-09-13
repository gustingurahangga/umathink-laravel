<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UmaThink - Belajar &amp; Bermain</title>

    <link rel="stylesheet" href="{{ asset('assets/css/verification.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo-section">
                <img src="{{ asset('assets/img/mailman.png') }}" alt="UmaThink Logo" class="logo">
            </div>

            <h2>Verification</h2>
            <p>Tolong Verifikasi Akun mu Melalui email Yang akan Kami Kirimkan</p>

            <form action="/verify" method="POST">
                @csrf
                <input type="hidden" value="register" name="type">
                <button type="submit" class="btn-login">Send OTP</button>
            </form>

            </form>
        </div>
    </div>

    <script src="{{ asset('assets/js/script.js') }}"></script>
</body>

</html>