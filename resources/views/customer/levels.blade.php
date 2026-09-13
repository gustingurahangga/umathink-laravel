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
    <link rel="stylesheet" href="{{ asset('assets/css/level-style.css') }}">

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
                        Pilih levelmu dan mulai belajar!
                    </div>
                </div>
            </header>

            <div class="level-selection-container">
                <div class="game-info-header">
                    <h1 class="game-title">{{ $game->nama_game }}</h1>
                    <div class="progress-info">
                        Progress: <span>{{ $completedLevels }} / {{ $totalLevels }}</span>
                    </div>
                </div>

                <div class="level-grid">
                    @for ($i = 1; $i <= $totalLevels; $i++)
                        @if ($i <= $completedLevels)
                            <a href="/games/{{ $game->slug }}/level/{{ $i }}" class="level-card completed">
                                <span class="level-num">{{ $i }}</span>
                                <div class="level-status">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                            </a>
                        @elseif ($i <= $unlockedLevels)
                            <a href="/games/{{ $game->slug }}/level/{{ $i }}" class="level-card unlocked">
                                <span class="level-num">{{ $i }}</span>
                                <div class="level-status">
                                    <i class="fa-solid fa-play"></i>
                                </div>
                            </a>
                        @else
                            <div class="level-card locked">
                                <span class="level-num">{{ $i }}</span>
                                <div class="level-status">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                            </div>
                        @endif
                    @endfor
                </div>

                <div class="back-action">
                    <a href="{{ route('game.dashboard') }}" class="btn-back">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </main>
    </div>

</body>
</html>