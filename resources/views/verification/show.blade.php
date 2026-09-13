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
                <h2>Yuk, Masukan Kodenya</h2>
                <p>Masukan Kode OTP untuk melanjutkan!</p>
            </div>
            <img src="{{ asset('assets/img/show.png') }}" alt="UmaThink Mascot" class="mascot">
        </div>

        <!-- BAGIAN KANAN -->
        <div class="right-section">

            @if(session('failed'))
            <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; font-size: 14px; border: 1px solid #f5c6cb;">
                {{ session('failed') }}
            </div>
            @endif

            @if(session('error'))
            <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; font-size: 14px; border: 1px solid #f5c6cb;">
                {{ session('error') }}
            </div>
            @endif

            <form action="/verify/{{$unique_id}}" method="POST" class="register-form">
                @csrf
                @method('PUT')

                <div class="input-group">
                    <i class="icon">🔗</i>
                    <input type="number" name="otp" placeholder="Masukan Kode Disini" required>
                </div>

                <button type="submit" class="btn-register">Submit</button>

                <p class="login-link">
                    Kirim Ulang OTP?
                    <a href="#!" onclick="event.preventDefault(); document.getElementById('resend-form').submit();">Kirim Ulang</a>
                </p>
            </form>

            <form id="resend-form" action="/verify" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="type" value="register">
            </form>
        </div>
    </div>

    <script>
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