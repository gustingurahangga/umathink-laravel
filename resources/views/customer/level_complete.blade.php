<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        UmaThink - Level {{ $levelNumber }}
    </title>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/style.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/game_dashboard.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/halamanplay.css') }}"
    >

    <link
        rel="icon"
        type="image/png"
        href="{{ asset('assets/img/logo.png') }}"
    >

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

            <a
                href="{{ route('game.dashboard') }}"
                class="nav-item active"
            >
                <i class="fa-solid fa-book-open"></i>
            </a>

            <a
                href="{{ route('game.leaderboard') }}"
                class="nav-item"
            >
                <i class="fa-solid fa-chart-simple"></i>
            </a>

            <a
                href="{{ route('customer.profile') }}"
                class="nav-item"
            >
                <i class="fa-solid fa-user"></i>
            </a>

        </nav>

    </aside>

    <main class="main-content">

        <div
            class="game-container complete-container"
            style="text-align:center;"
        >

            {{-- Trophy --}}
            <div class="trophy-wrapper">

                <div class="trophy-icon">
                    <i class="fa-solid fa-trophy"></i>
                </div>

                <div class="confetti-burst">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>

            </div>

            {{-- Alert --}}
            @if(session('success'))

                <div
                    style="
                        background:#e8f8ed;
                        color:#218838;
                        padding:15px;
                        border-radius:12px;
                        margin:20px auto;
                        max-width:550px;
                        font-weight:700;
                    "
                >
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                </div>

            @endif

            @if(session('error'))

                <div
                    style="
                        background:#ffe8e8;
                        color:#c0392b;
                        padding:15px;
                        border-radius:12px;
                        margin:20px auto;
                        max-width:550px;
                        font-weight:700;
                    "
                >
                    <i class="fa-solid fa-circle-xmark"></i>
                    {{ session('error') }}
                </div>

            @endif

            <h1 class="complete-title">
                Level {{ $levelNumber }} Selesai! 🎉
            </h1>

            <p class="complete-subtitle">
                Berikut hasil percobaanmu.
            </p>

            {{-- ============================= --}}
            {{-- HASIL PERCOBAAN --}}
            {{-- ============================= --}}

            <div
                style="
                    max-width:600px;
                    margin:25px auto;
                    padding:25px;
                    border-radius:20px;
                    background:#fff;
                    box-shadow:0 10px 30px rgba(0,0,0,.08);
                "
            >

                <h3 style="margin-top:0;">
                    Hasil Percobaan
                </h3>

                <div
                    style="
                        display:grid;
                        grid-template-columns:repeat(3,1fr);
                        gap:15px;
                    "
                >

                    <div>

                        <div
                            style="
                                font-size:30px;
                                font-weight:800;
                            "
                        >
                            {{ $attemptCorrect }}/10
                        </div>

                        <div style="color:#777;">
                            Jawaban Benar
                        </div>

                    </div>

                    <div>

                        <div
                            style="
                                font-size:30px;
                                font-weight:800;
                            "
                        >
                            {{ $attemptPoin }}
                        </div>

                        <div style="color:#777;">
                            Poin
                        </div>

                    </div>

                    <div>

                        <div
                            style="
                                font-size:25px;
                                color:#F8CB2E;
                                letter-spacing:2px;
                            "
                        >
                            @for($i = 1; $i <= 3; $i++)
                                {{ $i <= $attemptStars ? '★' : '☆' }}
                            @endfor
                        </div>

                        <div style="color:#777;">
                            Bintang
                        </div>

                    </div>

                </div>

            </div>

            {{-- ============================= --}}
            {{-- NILAI TERBAIK --}}
            {{-- ============================= --}}

            <div
                style="
                    max-width:600px;
                    margin:25px auto;
                    padding:25px;
                    border-radius:20px;
                    background:#fff8df;
                    border:2px solid #F8CB2E;
                "
            >

                <h3 style="margin-top:0;">
                    🏆 Nilai Terbaik Level Ini
                </h3>

                <div
                    style="
                        display:grid;
                        grid-template-columns:repeat(3,1fr);
                        gap:15px;
                    "
                >

                    <div>

                        <div
                            style="
                                font-size:30px;
                                font-weight:800;
                            "
                        >
                            {{ $bestCorrect }}/10
                        </div>

                        <div style="color:#777;">
                            Benar
                        </div>

                    </div>

                    <div>

                        <div
                            style="
                                font-size:30px;
                                font-weight:800;
                            "
                        >
                            {{ $bestPoin }}
                        </div>

                        <div style="color:#777;">
                            Poin
                        </div>

                    </div>

                    <div>

                        <div
                            style="
                                font-size:25px;
                                color:#F8CB2E;
                                letter-spacing:2px;
                            "
                        >

                            @for($i = 1; $i <= 3; $i++)

                                {{ $i <= $bestStars ? '★' : '☆' }}

                            @endfor

                        </div>

                        <div style="color:#777;">
                            Bintang
                        </div>

                    </div>

                </div>

            </div>

            {{-- Informasi skor --}}
            @if($isNewBest)

                <div
                    style="
                        color:#218838;
                        font-weight:700;
                        margin:20px 0;
                    "
                >
                    🎉 Nilai terbaikmu berhasil diperbarui!
                </div>

            @else

                <div
                    style="
                        color:#777;
                        font-weight:600;
                        margin:20px 0;
                    "
                >
                    Nilai percobaan ini belum mengalahkan nilai terbaikmu.
                </div>

            @endif

            {{-- Syarat level --}}
            @if($bestPoin < 18)

                <div
                    style="
                        max-width:550px;
                        margin:20px auto;
                        padding:15px;
                        border-radius:12px;
                        background:#ffe8e8;
                        color:#c0392b;
                        font-weight:700;
                    "
                >
                    🔒 Kamu membutuhkan minimal
                    <strong>18 poin / 1 bintang</strong>
                    untuk membuka level berikutnya.
                </div>

            @else

                @if($hasNextLevel)

                    <div
                        style="
                            max-width:550px;
                            margin:20px auto;
                            padding:15px;
                            border-radius:12px;
                            background:#e8f8ed;
                            color:#218838;
                            font-weight:700;
                        "
                    >
                        🔓 Level {{ $nextLevel }} berhasil dibuka!
                    </div>

                @endif

            @endif

            {{-- ============================= --}}
            {{-- BUTTON --}}
            {{-- ============================= --}}

            <div
                class="complete-actions"
                style="margin-top:30px;"
            >

                @if($hasNextLevel)

                    <a
                        href="{{ route('game.play', [
                            'slug' => $gameCategory->slug,
                            'level' => $nextLevel
                        ]) }}"
                        class="btn-complete btn-next-level"
                    >
                        <i class="fa-solid fa-forward"></i>

                        Lanjut ke Level {{ $nextLevel }}

                    </a>

                @endif

                {{-- Ulangi level --}}
                <a
                    href="{{ route('game.play', [
                        'slug' => $gameCategory->slug,
                        'level' => $levelNumber
                    ]) }}"
                    class="btn-complete"
                    style="
                        background:#F8CB2E;
                        color:#1a1a2e;
                    "
                >
                    <i class="fa-solid fa-rotate-right"></i>

                    Ulangi Level

                </a>

                <a
                    href="{{ route('game.levels', $gameCategory->slug) }}"
                    class="btn-complete btn-back-levels"
                >
                    <i class="fa-solid fa-layer-group"></i>

                    Pilih Level Lain

                </a>

                <a
                    href="{{ route('game.dashboard') }}"
                    class="btn-complete btn-dashboard"
                >
                    <i class="fa-solid fa-home"></i>

                    Kembali ke Dashboard

                </a>

            </div>

        </div>

    </main>

</div>

</body>
</html>