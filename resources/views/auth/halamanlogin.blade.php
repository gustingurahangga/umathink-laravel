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

            <h2>Mari Dapatkan Sesuatu!</h2>
            <p>Senang bisa melihatmu kembali di sini</p>

            @if(session('success'))
            <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; font-size: 14px; border: 1px solid #c3e6cb;">
                {{ session('success') }}
            </div>
            @endif

            @error('username')
            <div id="errorPopup" class="popup-error">
                {{ $message }}
            </div>
            @enderror



            <form action="/login" method="POST">
                @csrf
                <div class="input-group">
                    <label><i class="icon">👤</i></label>
                    <input type="text" name="username" placeholder="Username" required>
                </div>

                <div class="input-group">
                    <label><i class="icon">🔒</i></label>
                    <input type="password" name="password" placeholder="Password" id="password" required>
                    <span class="toggle-password" onclick="togglePassword(this)">🙉</span>
                </div>


                <div class="links">
                    <a href="{{ route('forgot.email.form') }}">Lupa Password?</a>
                    <span>Belum punya akun?
                        <a href="/register" class="register-link">Daftar</a>
                    </span>
                </div>

                <button type="submit" class="btn-login">Masuk</button>
 
                <div class="separator">
                    <span>atau</span>
                </div>

                <a href="/auth-google-redirect" class="btn-google">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google">
                    Masuk dengan Google
                </a>
            </form>
        </div>
    </div>

    <script src="{{ asset('assets/js/script.js') }}"></script>
</body>

</html>