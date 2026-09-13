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
                    <a href="{{ route('admin.users') }}" class="nav-item active">
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
                    <span class="stat-number">{{ $totalPengguna ?? 0 }}</span>
                    <span class="stat-desc">Pengguna Aktif</span>
                </div>
            </section>

            <div class="manage-header">
                <h2>Kelola Pengguna:</h2>
                <div class="action-controls">
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Cari Nama atau Username...">
                    </div>
                    <button class="btn-add" onclick="openAddModal()">Tambah</button>
                </div>
            </div>

            <div class="table-card">
                <div style="overflow-x: auto;">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>Nama Pengguna</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Proses</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($users) && count($users) > 0)
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->namalengkap ?? '-' }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td class="action-cells">
                                        <button class="btn-edit" onclick="openEditModal({{ $user->id }}, '{{ addslashes($user->namalengkap) }}', '{{ addslashes($user->username) }}', '{{ addslashes($user->email) }}')">Edit</button>
                                        <button class="btn-delete" onclick="confirmDelete({{ $user->id }})">Hapus</button>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" style="text-align: center;">Belum ada pengguna.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Form -->
    <div id="userModal" class="modal-overlay">
        <div class="modal-content">
            <h2 id="modalTitle">Edit Pengguna</h2>
            <form id="userForm" method="POST" action="">
                @csrf
                <input type="hidden" name="_method" value="PUT" id="methodPut">
                
                <div class="form-group">
                    <label>Nama Pengguna</label>
                    <input type="text" name="namalengkap" id="namalengkap" required>
                </div>
                
                <div class="form-group">
                    <label>Nama User</label>
                    <input type="text" name="username" id="username" required>
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="email" required>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" id="password" placeholder="Kosongkan jika tidak ingin mengubah password">
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
                // Jika baris adalah pesan "Belum ada pengguna", lewati
                if (row.cells.length < 4) return;

                // Nama Lengkap berada pada kolom ke-1 (index 0) dan Username pada kolom ke-2 (index 1)
                let nameCell = row.cells[0];
                let usernameCell = row.cells[1];
                
                if (nameCell || usernameCell) {
                    let nameText = nameCell ? (nameCell.textContent || nameCell.innerText) : '';
                    let usernameText = usernameCell ? (usernameCell.textContent || usernameCell.innerText) : '';
                    
                    if (nameText.toLowerCase().indexOf(filter) > -1 || usernameText.toLowerCase().indexOf(filter) > -1) {
                        row.style.display = '';
                        
                        // Menandakan baris jika tombol enter ditekan
                        if (e.key === 'Enter' && filter !== '') {
                            row.style.transition = 'background-color 0.5s';
                            row.style.backgroundColor = 'rgba(248, 203, 46, 0.4)'; // Warna kuning Umathink
                            
                            // Hilangkan highlight setelah 2 detik
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

        // Modal Functions
        function openAddModal() {
            document.getElementById('userModal').classList.add('active');
            document.getElementById('modalTitle').innerText = 'Tambah Pengguna';
            document.getElementById('userForm').action = '/admin/users';
            document.getElementById('methodPut').disabled = true;
            
            document.getElementById('namalengkap').value = '';
            document.getElementById('username').value = '';
            document.getElementById('email').value = '';
            document.getElementById('password').value = '';
            document.getElementById('password').required = true;
        }

        function openEditModal(id, namalengkap, username, email) {
            document.getElementById('userModal').classList.add('active');
            document.getElementById('modalTitle').innerText = 'Edit Pengguna';
            document.getElementById('userForm').action = '/admin/users/' + id;
            document.getElementById('methodPut').disabled = false;
            
            document.getElementById('namalengkap').value = namalengkap;
            document.getElementById('username').value = username;
            document.getElementById('email').value = email;
            document.getElementById('password').value = '';
            document.getElementById('password').required = false;
        }

        function closeModal() {
            document.getElementById('userModal').classList.remove('active');
        }

        // Close modal when clicking outside
        document.getElementById('userModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Delete Function
        function confirmDelete(id) {
            if (confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) {
                let form = document.getElementById('deleteForm');
                form.action = '/admin/users/' + id;
                form.submit();
            }
        }
    </script>
</body>
</html>