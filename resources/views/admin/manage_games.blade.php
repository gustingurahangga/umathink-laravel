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
    <link rel="stylesheet" href="{{ asset('assets/css/manage_users.css') }}">

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
                    <a href="{{ route('admin.dashboard') }}" class="nav-item">
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
                    <a href="{{ route('admin.games') }}" class="nav-item active">
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
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li><i class="fa-solid fa-circle-exclamation"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="stats-grid">
                <div class="stat-card">
                    <span class="stat-number">{{ $totalGame ?? 0 }}</span>
                    <span class="stat-desc">Game Tersedia</span>
                </div>
            </section>

            <div class="manage-header">
                <h2>Kelola Game:</h2>
                <div class="action-controls">
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Cari Nama Game...">
                    </div>
                    <button class="btn-add" onclick="openAddModal()">Tambah</button>
                </div>
            </div>

            <div class="table-card">
                <div style="overflow-x: auto;">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>Nama Game</th>
                                <th>Level Tersedia</th>
                                <th style="text-align: center;">Proses</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($games) && count($games) > 0)
                                @foreach($games as $game)
                                <tr>
                                    <td>{{ $game->nama_game }}</td>
                                    <td>{{ $game->jumlah_level }}</td>
                                    <td class="action-cells" style="display: flex; justify-content: flex-end; gap: 8px;">
                                        <button class="btn-edit" onclick="openEditModal({{ $game->id }}, '{{ addslashes($game->nama_game) }}', {{ $game->jumlah_level }})">Edit</button>
                                        <form method="POST" action="{{ route('admin.games.maintenance', $game->id) }}" style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" style="background-color: {{ $game->status === 'maintenance' ? '#4CAF50' : '#808080' }}; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: 600; width: 105px; text-align: center;">
                                                {{ $game->status === 'maintenance' ? 'Aktifkan' : 'Perawatan' }}
                                            </button>
                                        </form>
                                        <button class="btn-delete" onclick="confirmDelete({{ $game->id }})">Hapus</button>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" style="text-align: center;">Belum ada game.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Form -->
    <div id="gameModal" class="modal-overlay">
        <div class="modal-content">
            <h2 id="modalTitle">Edit Game</h2>
            <form id="gameForm" method="POST" action="">
                @csrf
                <input type="hidden" name="_method" value="PUT" id="methodPut">
                
                <div class="form-group">
                    <label>Nama Game</label>
                    <input type="text" name="nama_game" id="nama_game" required>
                </div>
                
                <div class="form-group">
                    <label>Jumlah Level</label>
                    <input type="number" name="jumlah_level" id="jumlah_level" min="1" required>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn-save">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Hidden Delete Form -->
    <form id="deleteForm" method="POST" action="" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function(e) {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('.user-table tbody tr');

            rows.forEach(row => {
                if (row.cells.length < 3) return;

                let nameCell = row.cells[0];
                
                if (nameCell) {
                    let nameText = nameCell.textContent || nameCell.innerText;
                    
                    if (nameText.toLowerCase().indexOf(filter) > -1) {
                        row.style.display = '';
                        
                        if (e.key === 'Enter' && filter !== '') {
                            row.style.transition = 'background-color 0.5s';
                            row.style.backgroundColor = 'rgba(248, 203, 46, 0.4)';
                            
                            setTimeout(() => {
                                row.style.backgroundColor = '';
                            }, 2000);
                        }
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        });

        function openAddModal() {
            document.getElementById('gameModal').classList.add('active');
            document.getElementById('modalTitle').innerText = 'Tambah Game';
            document.getElementById('gameForm').action = '/admin/games';
            document.getElementById('methodPut').disabled = true;
            
            document.getElementById('nama_game').value = '';
            document.getElementById('jumlah_level').value = '';
        }

        function openEditModal(id, nama_game, jumlah_level) {
            document.getElementById('gameModal').classList.add('active');
            document.getElementById('modalTitle').innerText = 'Edit Game';
            document.getElementById('gameForm').action = '/admin/games/' + id;
            document.getElementById('methodPut').disabled = false;
            
            document.getElementById('nama_game').value = nama_game;
            document.getElementById('jumlah_level').value = jumlah_level;
        }

        function closeModal() {
            document.getElementById('gameModal').classList.remove('active');
        }

        document.getElementById('gameModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        function confirmDelete(id) {
            if (confirm('Apakah Anda yakin ingin menghapus game ini?')) {
                let form = document.getElementById('deleteForm');
                form.action = '/admin/games/' + id;
                form.submit();
            }
        }
    </script>
</body>
</html>