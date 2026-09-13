<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UmaThink - Belajar &amp; Bermain</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/game_dashboard.css') }}">

    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
</head>
<body>

    @include('layouts.app')

    <div class="app-container">
        <aside class="sidebar">
            <div class="logo-top">
                <img src="{{ asset('assets/img/umathink_logo_text.png') }}" alt="UmaThink" class="sidebar-logo">
            </div>
            <nav class="side-nav">
                <a href="{{ route('game.dashboard') }}" class="nav-item active">
                    <i class="fa-solid fa-book-open"></i>
                </a>
                <a href="{{ route('game.leaderboard') }}" class="nav-item">
                    <i class="fa-solid fa-chart-simple"></i>
                </a>
                <a href="{{ route('customer.profile') }}" class="nav-item">
                    <i class="fa-solid fa-user"></i>
                </a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="header-section">
                <div class="mascot-wrapper">
                    <img src="{{ asset('assets/img/maskot.png') }}" alt="Maskot" class="mini-mascot">
                    <div class="speech-bubble">
                        Selamat Mengerjakan!
                    </div>
                </div>
            </header>

            <section class="category-grid">
                @forelse($games as $game)
                <div class="category-card">
                    <div class="card-header">
                        <h2>{{ $game->nama_game }}</h2>
                        <i class="fa-solid fa-circle-info info-icon" onclick="toggleInfo(this)"></i>
                        <div class="info-popup">
                            Uji kemampuan Anda dalam kategori {{ $game->nama_game }}. Selesaikan semua level untuk mendapatkan poin maksimal!
                        </div>
                    </div>
                    <p class="completion">{{ $game->completed_levels ?? 0 }} / {{ $game->jumlah_level }} Completion</p>
                    @if(isset($game->is_locked) && $game->is_locked)
                        <button class="btn-start" style="background-color: #ccc; color: #666; cursor: not-allowed; border: none; width: 100%;" onclick="alert('Selesaikan semua game lain terlebih dahulu untuk membuka Tryout!')">
                            <i class="fa-solid fa-lock" style="margin-right: 5px;"></i> TERKUNCI
                        </button>
                    @else
                        <a href="{{ route('game.levels', $game->slug) }}" class="btn-start">START</a>
                    @endif
                </div>
                @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 50px; color: #888;">
                    <i class="fa-solid fa-gamepad" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
                    <p>Belum ada game tersedia. Silakan hubungi admin.</p>
                </div>
                @endforelse
            </section>
        </main>
    </div>

    <script>
        function toggleInfo(iconElement) {
            // Tutup semua popup yang sedang terbuka (opsional)
            const allPopups = document.querySelectorAll('.info-popup');
            const targetPopup = iconElement.nextElementSibling;
            
            allPopups.forEach(popup => {
                if(popup !== targetPopup) {
                    popup.classList.remove('show');
                }
            });

            // Toggle popup pada icon yang ditekan
            targetPopup.classList.toggle('show');
        }

        // Tutup popup jika user klik di luar popup
        document.addEventListener('click', function(event) {
            if (!event.target.classList.contains('info-icon') && !event.target.closest('.info-popup')) {
                document.querySelectorAll('.info-popup').forEach(popup => {
                    popup.classList.remove('show');
                });
            }
        });
    </script>

</body>
</html>