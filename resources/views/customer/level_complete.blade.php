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
    <link rel="stylesheet" href="{{ asset('assets/css/halamanplay.css') }}">

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
            <div class="game-container complete-container">

                {{-- Trophy Animation --}}
                <div class="trophy-wrapper">
                    <div class="trophy-icon">
                        <i class="fa-solid fa-trophy"></i>
                    </div>
                    <div class="confetti-burst">
                        <span></span><span></span><span></span>
                        <span></span><span></span><span></span>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert-feedback alert-success" id="alertFeedback" style="margin-bottom: 20px; width: 100%; max-width: 500px; margin-left: auto; margin-right: auto; text-align: center; border-radius: 12px; padding: 15px;">
                        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert-feedback alert-error" id="alertFeedbackError" style="margin-bottom: 20px; width: 100%; max-width: 500px; margin-left: auto; margin-right: auto; text-align: center; border-radius: 12px; padding: 15px;">
                        <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
                    </div>
                @endif

                @if(session('pembahasan'))
                    <div class="alert-feedback info-box-custom" style="display: flex; flex-direction: column; align-items: flex-start; text-align: left; padding: 15px; margin-bottom: 30px; width: 100%; max-width: 500px; margin-left: auto; margin-right: auto; border-radius: 12px;">
                        <div style="font-weight: 800; margin-bottom: 8px; font-size: 14px;"><i class="fa-solid fa-lightbulb" style="color: #F8CB2E; margin-right: 5px;"></i> Pembahasan Soal Terakhir:</div>
                        <div style="font-size: 14px; line-height: 1.5; font-weight: 600;">{{ session('pembahasan') }}</div>
                    </div>
                @endif

                <h1 class="complete-title">Level {{ $levelNumber }} Selesai! 🎉</h1>
                <p class="complete-subtitle">Kamu berhasil menyelesaikan semua soal di level ini!</p>

                {{-- Stats Cards --}}
                <div class="stats-row">
                    <div class="stat-card stat-questions">
                        <div class="stat-icon"><i class="fa-solid fa-circle-question"></i></div>
                        <div class="stat-value">{{ $totalQuestions }}</div>
                        <div class="stat-label">Soal Dijawab</div>
                    </div>
                    <div class="stat-card stat-points">
                        <div class="stat-icon"><i class="fa-solid fa-star"></i></div>
                        <div class="stat-value">+{{ $totalPoin }}</div>
                        <div class="stat-label">Poin Didapat</div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="complete-actions">
                    @if($hasNextLevel)
                        <a href="{{ route('game.play', ['slug' => $gameCategory->slug, 'level' => $nextLevel]) }}" class="btn-complete btn-next-level">
                            <i class="fa-solid fa-forward"></i> Lanjut ke Level {{ $nextLevel }}
                        </a>
                    @endif

                    <a href="{{ route('game.levels', $gameCategory->slug) }}" class="btn-complete btn-back-levels">
                        <i class="fa-solid fa-layer-group"></i> Pilih Level Lain
                    </a>

                    <a href="{{ route('game.dashboard') }}" class="btn-complete btn-dashboard">
                        <i class="fa-solid fa-home"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Trigger confetti animation on load
        document.addEventListener('DOMContentLoaded', () => {
            const trophy = document.querySelector('.trophy-wrapper');
            if (trophy) {
                trophy.classList.add('animate');
            }
        });

        // Auto-hide alert setelah 3 detik (kecuali pembahasan)
        ['alertFeedback', 'alertFeedbackError'].forEach(id => {
            const alert = document.getElementById(id);
            if (alert) {
                setTimeout(() => {
                    alert.style.animation = 'fadeOutAlert 0.4s ease-out forwards';
                    setTimeout(() => alert.remove(), 400);
                }, 3000);
            }
        });
    </script>
</body>
</html>
