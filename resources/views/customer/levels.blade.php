<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>UmaThink - {{ $game->nama_game }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet"
          href="{{ asset('assets/css/style.css') }}">

    <link rel="stylesheet"
          href="{{ asset('assets/css/game_dashboard.css') }}">

    <link rel="stylesheet"
          href="{{ asset('assets/css/level-style.css') }}">

    <link rel="icon"
          type="image/png"
          href="{{ asset('assets/img/logo.png') }}">
</head>

<body>

@include('layouts.app')

<div class="app-container">

    <aside class="sidebar">

        <div class="logo-top">
            <img
                src="{{ asset('assets/img/umathink_logo_text.png') }}"
                alt="UmaThink"
                class="sidebar-logo"
            >
        </div>

        <nav class="side-nav">

            <a href="{{ route('game.dashboard') }}"
               class="nav-item active">
                <i class="fa-solid fa-book-open"></i>
            </a>

            <a href="{{ route('game.leaderboard') }}"
               class="nav-item">
                <i class="fa-solid fa-chart-simple"></i>
            </a>

            <a href="{{ route('customer.profile') }}"
               class="nav-item">
                <i class="fa-solid fa-user"></i>
            </a>

        </nav>

    </aside>

    <main class="main-content">

        <header class="header-section">

            <div class="mascot-wrapper">

                <img
                    src="{{ asset('assets/img/maskot.png') }}"
                    alt="Maskot"
                    class="mini-mascot"
                >

                <div class="speech-bubble">
                    Pilih levelmu dan mulai belajar!
                </div>

            </div>

        </header>

        <div class="level-selection-container">

            <div class="game-info-header">

                <div>
                    <h1 class="game-title">
                        {{ $game->nama_game }}
                    </h1>

                    <p style="margin: 5px 0 0; color: #777;">
                        Minimal ⭐ untuk membuka level berikutnya
                    </p>
                </div>

                <div class="progress-info">
                    Progress:
                    <span>
                        {{ $completedLevels }} / {{ $totalLevels }}
                    </span>
                </div>

            </div>

            {{-- Alert --}}
            @if(session('error'))

                <div
                    style="
                        background:#ffe8e8;
                        color:#c0392b;
                        padding:14px 18px;
                        border-radius:12px;
                        margin-bottom:25px;
                        font-weight:600;
                        text-align:center;
                    "
                >
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ session('error') }}
                </div>

            @endif

            @if(session('success'))

                <div
                    style="
                        background:#e7f8ed;
                        color:#218838;
                        padding:14px 18px;
                        border-radius:12px;
                        margin-bottom:25px;
                        font-weight:600;
                        text-align:center;
                    "
                >
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                </div>

            @endif

            <div class="level-grid">

                @for ($i = 1; $i <= $totalLevels; $i++)

                    @php
                        $score = $levelScores[$i] ?? null;

                        $stars = $score?->stars ?? 0;
                        $poin = $score?->poin ?? 0;

                        $isUnlocked = $i <= $unlockedLevels;
                        $isCompleted = $score !== null && $poin >= 18;
                    @endphp

                    @if($isUnlocked)

                        <a
                            href="{{ route('game.play', [
                                'slug' => $game->slug,
                                'level' => $i
                            ]) }}"
                            class="level-card {{ $isCompleted ? 'completed' : 'unlocked' }}"
                        >

                            <span class="level-num">
                                {{ $i }}
                            </span>

                            {{-- Bintang --}}
                            <div
                                style="
                                    margin-top:8px;
                                    font-size:18px;
                                    letter-spacing:2px;
                                    color:#F8CB2E;
                                "
                            >
                                @for($s = 1; $s <= 3; $s++)

                                    @if($s <= $stars)
                                        ★
                                    @else
                                        ☆
                                    @endif

                                @endfor
                            </div>

                            {{-- Poin --}}
                            <div
                                style="
                                    margin-top:5px;
                                    font-size:13px;
                                    font-weight:700;
                                    color:{{ $isCompleted ? '#218838' : '#777' }};
                                "
                            >
                                {{ $poin }} Poin
                            </div>

                            <div class="level-status">

                                @if($isCompleted)
                                    <i class="fa-solid fa-rotate"></i>
                                @else
                                    <i class="fa-solid fa-play"></i>
                                @endif

                            </div>

                        </a>

                    @else

                        <div class="level-card locked">

                            <span class="level-num">
                                {{ $i }}
                            </span>

                            <div
                                style="
                                    margin-top:8px;
                                    font-size:18px;
                                    letter-spacing:2px;
                                    color:#aaa;
                                "
                            >
                                ☆☆☆
                            </div>

                            <div
                                style="
                                    margin-top:5px;
                                    font-size:12px;
                                    font-weight:600;
                                    color:#999;
                                    text-align:center;
                                "
                            >
                                Minimal 18 poin
                            </div>

                            <div class="level-status">
                                <i class="fa-solid fa-lock"></i>
                            </div>

                        </div>

                    @endif

                @endfor

            </div>

            <div class="back-action">

                <a
                    href="{{ route('game.dashboard') }}"
                    class="btn-back"
                >
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali ke Dashboard
                </a>

            </div>

        </div>

    </main>

</div>

</body>
</html>