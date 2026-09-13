<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UmaThink - Belajar &amp; Bermain</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/leaderboard.css') }}">

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
                <a href="{{ route('game.dashboard') }}" class="nav-item">
                    <i class="fa-solid fa-book-open"></i>
                </a>
                <a href="{{ route('game.leaderboard') }}" class="nav-item active">
                    <i class="fa-solid fa-chart-simple"></i>
                </a>
                <a href="{{ route('customer.profile') }}" class="nav-item">
                    <i class="fa-solid fa-user"></i>
                </a>
            </nav>
        </aside>

        <main class="main-content">
            <section class="stats-row">
                <div class="stat-card">
                    <div class="icon-circle yellow">
                        <img src="{{ asset('assets/img/star.png') }}" alt="Peringkat" style="width: 28px; height: 28px; object-fit: contain;">
                    </div>
                    <div class="stat-info">
                        <span class="stat-value">{{ auth()->user()->peringkat }}</span>
                        <span class="stat-label">Peringkat</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="icon-circle orange">
                        <img src="{{ asset('assets/img/trophy.png') }}" alt="Total Poin" style="width: 28px; height: 28px; object-fit: contain;">
                    </div>
                    <div class="stat-info">
                        <span class="stat-value">{{ auth()->user()->total_poin ?? 0 }}</span>
                        <span class="stat-label">Total Poin</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="icon-circle green">
                        <img src="{{ asset('assets/img/medal.png') }}" alt="Liga" style="width: 28px; height: 28px; object-fit: contain;">
                    </div>
                    <div class="stat-info">
                        <span class="stat-value">{{ auth()->user()->liga }}</span>
                        <span class="stat-label">Liga</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="icon-circle orange">
                        <i class="fa-solid fa-hourglass-half" style="font-size: 22px; color: #E67E22;"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value">{{ $seasonDaysLeft ?? 90 }} Hari</span>
                        <span class="stat-label">Sisa Season</span>
                    </div>
                </div>
            </section>

            <section class="leaderboard-container">
                <div class="leaderboard-axis">
                    <div class="table-header">
                        <span class="col-user" style="text-align: left; padding-left: 20px;">Username</span>
                        <span class="col-points">Total Poin</span>
                        <span class="col-league">Liga</span>
                    </div>

                    <div class="rank-list">
                        @php $isCurrentUserInTop10 = false; @endphp
                        @forelse($users as $index => $user)
                            @if(auth()->check() && auth()->id() === $user->id)
                                @php $isCurrentUserInTop10 = true; @endphp
                            @endif
                        <div class="rank-item {{ auth()->check() && auth()->id() === $user->id ? 'current-user-row' : '' }}">
                            <span class="rank-number">{{ sprintf('%02d', $index + 1) }}.</span>
                            <div class="rank-content" style="display: flex; align-items: center; width: 100%;">
                                <span class="col-user" style="flex: 2; text-align: left; font-weight: 600; font-size: 18px; display: flex; align-items: center; justify-content: flex-start; gap: 15px; padding-left: 20px;">
                                    <img src="{{ $user->photo ?: ($user->avatar ?: asset('assets/img/default-user.png')) }}" alt="{{ $user->username }}" class="leaderboard-avatar">
                                    {{ $user->username }}
                                </span>
                                <span class="col-points" style="flex: 1; text-align: center; font-weight: 600; font-size: 18px;">{{ $user->total_poin ?? 0 }}</span>
                                @php
                                    $liga = $user->liga ?? 'Bronze';
                                    $ligaLower = strtolower($liga);
                                    $imgSrc = asset("assets/img/{$ligaLower}.png");
                                @endphp
                                <span class="col-league" style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 10px; font-weight: 600; font-size: 18px;">
                                    <span>{{ $liga }}</span>
                                    <div class="league-wrapper league-{{ $ligaLower }}">
                                        <img src="{{ $imgSrc }}" class="league-icon" onerror="this.src='{{ asset('assets/img/medal.png') }}'" alt="{{ $liga }}">
                                    </div>
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="rank-item">
                            <span class="rank-number">-</span>
                            <div class="rank-content" style="display: flex; align-items: center; width: 100%;">
                                <span style="flex: 1; text-align: center; color: #888;">Belum ada pengguna.</span>
                            </div>
                        </div>
                        @endforelse

                        {{-- Tampilkan user saat ini di bawah jika tidak masuk top 10 --}}
                        @if(auth()->check() && auth()->user()->role === 'customer' && !$isCurrentUserInTop10)
                        <div style="margin-top: 20px; padding-top: 20px; border-top: 2px dashed rgba(0,0,0,0.1);">
                            <div class="rank-item current-user-row">
                                <span class="rank-number">{{ sprintf('%02d', auth()->user()->peringkat) }}.</span>
                                <div class="rank-content" style="display: flex; align-items: center; width: 100%;">
                                    <span class="col-user" style="flex: 2; text-align: left; font-weight: 600; font-size: 18px; display: flex; align-items: center; justify-content: flex-start; gap: 15px; padding-left: 20px;">
                                        <img src="{{ auth()->user()->photo ?: (auth()->user()->avatar ?: asset('assets/img/default-user.png')) }}" alt="{{ auth()->user()->username }}" class="leaderboard-avatar">
                                        {{ auth()->user()->username }}
                                    </span>
                                    <span class="col-points" style="flex: 1; text-align: center; font-weight: 600; font-size: 18px;">{{ auth()->user()->total_poin ?? 0 }}</span>
                                    @php
                                        $ligaAuth = auth()->user()->liga ?? 'Bronze';
                                        $ligaAuthLower = strtolower($ligaAuth);
                                        $imgSrcAuth = asset("assets/img/{$ligaAuthLower}.png");
                                    @endphp
                                    <span class="col-league" style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 10px; font-weight: 600; font-size: 18px;">
                                        <span>{{ $ligaAuth }}</span>
                                        <div class="league-wrapper league-{{ $ligaAuthLower }}">
                                            <img src="{{ $imgSrcAuth }}" class="league-icon" onerror="this.src='{{ asset('assets/img/medal.png') }}'" alt="{{ $ligaAuth }}">
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </section>
        </main>
    </div>

<style>
.leaderboard-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e0e0e0;
    flex-shrink: 0;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
.current-user-row .leaderboard-avatar {
    border-color: #F8CB2E;
}
.league-icon {
    width: 36px;
    height: 36px;
    object-fit: contain;
    position: relative;
    z-index: 2;
}
.league-wrapper {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
</style>

</body>
</html>