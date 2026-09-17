<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UmaThink - Belajar &amp; Bermain</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin_dashboard.css') }}">

    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
</head>
<body>

    @include('layouts.app')

    <div class="admin-container">
        <aside class="sidebar">
            <div class="logo-top">
                <img src="{{ asset('assets/img/umathink_logo_text.png') }}" alt="UmaThink" class="sidebar-logo">
            </div>

            <nav class="sidebar-nav">
                <div class="nav-group">
                    <p class="nav-label">Dash</p>
                    <a href="{{ route('admin.dashboard') }}" class="nav-item active">
                        <i class="fa-solid fa-gauge-high"></i>
                    </a>
                </div>

                <div class="nav-group">
                    <p class="nav-label">User</p>
                    <a href="{{ route('admin.users') }}" class="nav-item">
                        <i class="fa-solid fa-users"></i>
                    </a>

                </div>

                <div class="nav-group">
                    <p class="nav-label">Game</p>
                    <a href="{{ route('admin.games') }}" class="nav-item">
                        <i class="fa-solid fa-users-gear"></i>
                    </a>
                    <a href="{{ route('admin.questions') }}" class="nav-item">
                        <i class="fa-solid fa-book-open"></i>
                    </a>
                </div>
            </nav>
        </aside>

        <main class="main-content">
            @if(session('success'))
                <div class="alert-feedback alert-success" style="background: rgba(76, 175, 80, 0.1); border: 1px solid #4CAF50; color: #4CAF50; padding: 15px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            <section class="stats-grid">
                <div class="stat-card">
                    <span class="stat-number">{{ $totalPengguna ?? 0 }}</span>
                    <span class="stat-desc">Pengguna Aktif</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">{{ $totalGame ?? 0 }}</span>
                    <span class="stat-desc">Game Tersedia</span>
                </div>

                <div class="stat-card">
                    <span class="stat-number">{{ $soalCount ?? 0 }}</span>
                    <span class="stat-desc">Soal Tersedia</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">{{ $totalLevel ?? 0 }}</span>
                    <span class="stat-desc">Level Tersedia</span>
                </div>
            </section>

            <section class="data-row">
                <div class="table-container activity-table">
                    <h3>Aktivitas Terbaru:</h3>
                    <div class="white-box">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Game Terakhir</th>
                                    <th>Level</th>
                                    <th>Poin</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody id="activityTableBody">
                                @if(isset($activity) && count($activity) > 0)
                                    @foreach($activity as $act)
                                    <tr>
                                        <td>{{ $act->username ?? '-' }}</td>
                                        <td>
                                            <span style="background-color: {{ $act->type == 'register' ? 'rgba(76, 175, 80, 0.2)' : 'rgba(248, 203, 46, 0.2)' }}; padding: 3px 10px; border-radius: 6px; font-weight: 700; font-size: 13px;">
                                                {{ $act->game ?? 'Belum Ada' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span style="font-weight: 800; font-size: 13px;">{{ $act->type == 'register' ? '-' : 'Lvl ' . $act->level }}</span>
                                        </td>
                                        <td style="font-weight: 700;">{{ $act->total_poin ?? 0 }}</td>
                                        <td style="font-size: 12px; color: #888;">{{ $act->time_human }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" style="text-align: center;">Belum ada aktivitas game.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="table-container best-player">
                    <h3>Pemain Terbaik</h3>
                    <div class="white-box">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Poin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($topPlayers) && count($topPlayers) > 0)
                                    @foreach($topPlayers as $player)
                                    <tr>
                                        <td>{{ $player->username }}</td>
                                        <td>{{ $player->total_poin ?? 0 }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2" style="text-align: center;">Tidak ada data pemain.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <style>
        @keyframes pulse {
            0% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.8); }
            100% { opacity: 1; transform: scale(1); }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        #activityTableBody tr {
            transition: background-color 0.3s ease;
        }
        #activityTableBody tr.new-row {
            animation: fadeIn 0.4s ease;
        }
    </style>

    <script>
        // Auto-refresh aktivitas terbaru setiap 10 detik
        const REFRESH_INTERVAL = 10000;

        function fetchRecentActivity() {
            fetch('{{ route("admin.api.recent-activity") }}')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('activityTableBody');
                    
                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="5" style="text-align: center;">Belum ada aktivitas game.</td></tr>';
                        return;
                    }

                    let html = '';
                    data.forEach(item => {
                        html += `<tr class="new-row">
                            <td>${item.username}</td>
                            <td>
                                <span style="background-color: ${item.type == 'register' ? 'rgba(76, 175, 80, 0.2)' : 'rgba(248, 203, 46, 0.2)'}; padding: 3px 10px; border-radius: 6px; font-weight: 700; font-size: 13px;">
                                    ${item.game}
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 800; font-size: 13px;">${item.type == 'register' ? '-' : 'Lvl ' + item.level}</span>
                            </td>
                            <td style="font-weight: 700;">${item.total_poin}</td>
                            <td style="font-size: 12px; color: #888;">${item.updated_at}</td>
                        </tr>`;
                    });

                    tbody.innerHTML = html;


                })
                .catch(error => {
                    console.error('Gagal memuat aktivitas:', error);
                });
        }

        // Jalankan auto-refresh
        setInterval(fetchRecentActivity, REFRESH_INTERVAL);


    </script>

</body>
</html>

