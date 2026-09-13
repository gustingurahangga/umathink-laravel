<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UmaThink - Belajar &amp; Bermain</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/register.css') }}">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
</head>

<body>
    <div class="register-container">

        <!-- BAGIAN KIRI -->
        <div class="left-section">
            <div class="text-content">
                <h2>Yuk, Buat Akun Baru!</h2>
                <p>Buat akun untuk melanjutkan!</p>
            </div>
            <img src="{{ asset('assets/img/uma-register.png') }}" alt="UmaThink Mascot" class="mascot">
        </div>

        <!-- BAGIAN KANAN -->
        <div class="right-section">

            {{-- POPUP ERROR --}}
            @if ($errors->any())
            <div id="errorPopup" class="popup-error show">
                <div class="popup-content">
                    <span class="popup-icon">⚠️</span>
                    <div class="popup-text">
                        @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                        @endforeach
                    </div>
                    <span class="popup-close" onclick="closePopup()">✖</span>
                </div>
            </div>
            @endif

            <form action="/register" method="POST" class="register-form">
                @csrf

                <div class="input-group">
                    <i class="icon">👤</i>
                    <input type="text" name="username" placeholder="Nama Pengguna"
                        value="{{ old('username') }}">
                </div>

                <div class="input-group">
                    <i class="icon">👥</i>
                    <input type="text" name="namalengkap" placeholder="Nama Lengkap"
                        value="{{ old('namalengkap') }}">
                </div>

                <div class="input-group">
                    <i class="icon">📧</i>
                    <input type="email" name="email" placeholder="Email"
                        value="{{ old('email') }}">
                </div>

                <div class="input-group">
                    <i class="icon">🔒</i>
                    <input type="password" id="password" name="password" placeholder="Password">
                    <span class="toggle" onclick="togglePassword('password', this)">🙉</span>
                </div>

                <div class="input-group">
                    <i class="icon">🔐</i>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Ulangi Password">
                    <span class="toggle" onclick="togglePassword('confirm_password', this)">🙉</span>
                </div>

                <button type="submit" class="btn-register">Daftar</button>

                <p class="login-link">
                    Sudah punya akun?
                    <a href="/login">Masuk</a>
                </p>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(id, el) {
            const input = document.getElementById(id);

            if (input.type === 'password') {
                input.type = 'text';
                el.textContent = '🙈';
            } else {
                input.type = 'password';
                el.textContent = '🙉';
            }
        }

        function closePopup() {
            const popup = document.getElementById('errorPopup');
            if (popup) popup.classList.remove('show');
        }

        setTimeout(() => {
            closePopup();
        }, 5000);
    </script>
</body>

</html>