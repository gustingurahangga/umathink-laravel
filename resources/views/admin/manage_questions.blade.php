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
    <style>
        .upload-area {
            border: 2px dashed #F8CB2E;
            border-radius: 15px;
            padding: 30px 20px;
            text-align: center;
            background-color: #FFFDF5;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }
        .upload-area:hover {
            border-color: #2b4c7e;
            background-color: #F4F7FC;
        }
        .upload-area i {
            transition: transform 0.3s;
        }
        .upload-area:hover i {
            transform: translateY(-5px);
            color: #2b4c7e;
        }
        .btn-select {
            background-color: #F8CB2E;
            border: none;
            padding: 10px 25px;
            border-radius: 10px;
            font-weight: 800;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.2s;
        }
        .btn-select:hover {
            background-color: #e0b41f;
            transform: scale(1.03);
        }
        .btn-template {
            transition: color 0.2s;
        }
        .btn-template:hover {
            color: #28a745 !important;
        }
        .pagination {
            display: flex;
            justify-content: center;
            gap: 5px;
            list-style: none;
            padding: 0;
            margin: 20px 0 0 0;
        }
        .pagination li a, .pagination li span {
            padding: 8px 16px;
            border-radius: 8px;
            background-color: #fff;
            color: #333;
            text-decoration: none;
            font-weight: 700;
            border: 1px solid #EEE;
            transition: all 0.2s;
        }
        .pagination li.active span {
            background-color: #F8CB2E;
            border-color: #F8CB2E;
            color: #000;
        }
        .pagination li.disabled span {
            color: #999;
            border-color: #EEE;
            background-color: #F9F9F9;
            cursor: not-allowed;
        }
        .pagination li a:hover {
            background-color: #EEE;
        }
        body.dark-mode .upload-area {
            background-color: #1c1c30;
            border-color: #3b3b5c;
        }
        body.dark-mode .upload-area:hover {
            border-color: #F8CB2E;
            background-color: #23233c;
        }
        body.dark-mode .pagination li a, body.dark-mode .pagination li span {
            background-color: #252542;
            border-color: #3b3b5c;
            color: #f8f9fa;
        }
        body.dark-mode .pagination li.active span {
            background-color: #F8CB2E;
            color: #1a1a2e;
            border-color: #F8CB2E;
        }
        body.dark-mode .pagination li.disabled span {
            background-color: #1c1c30;
            border-color: #3b3b5c;
            color: #666;
        }
        
        /* Edit Modal Dark Mode Fix */
        .edit-modal-content {
            background: #fff;
            color: #333;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        body.dark-mode .edit-modal-content {
            background: #1c1c30;
            color: #f8f9fa;
        }
        
        body.dark-mode .edit-modal-content input,
        body.dark-mode .edit-modal-content select,
        body.dark-mode .edit-modal-content textarea {
            background: #252542 !important;
            color: #f8f9fa !important;
            border-color: #3b3b5c !important;
        }
        
        body.dark-mode .edit-modal-content h3,
        body.dark-mode .edit-modal-content label {
            color: #f8f9fa;
        }
        
        body.dark-mode .btn-cancel-modal {
            background: #3b3b5c !important;
            color: #f8f9fa !important;
        }

        .teks-soal-cell {
            font-weight: 700;
            color: #111;
        }

        body.dark-mode .teks-soal-cell {
            color: #f8f9fa !important;
        }
    </style>

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
                    <a href="{{ route('admin.games') }}" class="nav-item">
                        <i class="fa-solid fa-users-gear"></i>
                    </a>
                    <a href="{{ route('admin.questions') }}" class="nav-item active">
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
                    <span class="stat-number">{{ $totalSoal ?? 0 }}</span>
                    <span class="stat-desc">Soal Tersedia</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">{{ $totalGame ?? 0 }}</span>
                    <span class="stat-desc">Kategori Game</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">{{ $totalLevelMax ?? 0 }}</span>
                    <span class="stat-desc">Level Maksimum</span>
                </div>
            </section>

            <!-- Card Unggah Excel / CSV -->
            <div class="table-card" style="margin-bottom: 30px; min-height: auto; padding: 25px;">
                <h3 style="margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-file-excel" style="color: #28a745; font-size: 28px;"></i>
                    <span>Impor Soal dan Jawaban Baru</span>
                </h3>
                
                <form action="{{ route('admin.questions.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="upload-area" id="uploadArea" onclick="document.getElementById('file_soal').click()">
                        <i class="fa-solid fa-cloud-arrow-up" style="font-size: 44px; color: #F8CB2E; margin-bottom: 10px; display: block;"></i>
                        <p style="font-weight: 700; margin-bottom: 5px; font-size: 16px;">Tarik & Lepas file Excel (.xlsx / .xls) atau CSV di sini</p>
                        <p style="font-size: 13px; color: #888; margin-bottom: 15px;">Atau klik untuk menelusuri folder di komputer Anda</p>
                        <span style="font-size: 11px; color: #aaa; background-color: rgba(0,0,0,0.03); padding: 4px 8px; border-radius: 4px; display: inline-block;">Ukuran Maks: 4MB</span>
                        
                        <input type="file" name="file_soal" id="file_soal" style="display: none;" accept=".xlsx,.xls,.csv,.txt" required>
                        <div id="fileInfo" style="margin-top: 15px; display: none;">
                            <span style="background-color: #28a745; color: white; padding: 6px 15px; border-radius: 20px; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 5px;">
                                <i class="fa-solid fa-file-circle-check"></i> <span id="fileName">Nama_File.xlsx</span>
                            </span>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; flex-wrap: wrap; gap: 15px;">
                        <a href="{{ route('admin.questions.template') }}" class="btn-template" style="text-decoration: none; color: #666; font-weight: 700; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-file-excel" style="font-size: 20px; color: #28a745;"></i>
                            <span>Unduh Template Excel (.xlsx)</span>
                        </a>
                        
                        <button type="submit" class="btn-save" style="background-color: #2b4c7e; color: #FFF; border: none; padding: 12px 30px; border-radius: 12px; font-weight: 800; cursor: pointer; transition: transform 0.2s; box-shadow: 0 4px 10px rgba(0,0,0,0.1); display: inline-flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-upload"></i>
                            <span>Mulai Impor</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel Daftar Soal -->
            <div class="table-card">
                <div class="manage-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                    <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                        <h2 style="margin: 0;">Daftar Soal Tersedia</h2>
                        @if(isset($totalSoal) && $totalSoal > 0)
                            <button type="button" onclick="confirmDeleteAll()" style="background-color: #E74C3C; color: white; border: none; padding: 9px 18px; border-radius: 8px; font-weight: 700; font-family: 'Inter', sans-serif; cursor: pointer; display: inline-flex; align-items: center; gap: 7px; font-size: 13px; box-shadow: 0 3px 8px rgba(231, 76, 60, 0.25); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 12px rgba(231, 76, 60, 0.35)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 3px 8px rgba(231, 76, 60, 0.25)'">
                                <i class="fa-solid fa-trash-can"></i> Kosongkan Semua Soal
                            </button>
                        @endif
                    </div>
                    
                    <form method="GET" action="{{ route('admin.questions') }}" style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <input type="text" name="search" placeholder="Cari soal..." value="{{ request('search') }}" style="padding: 10px 15px; border-radius: 10px; border: 1px solid #ddd; font-weight: 600; outline: none; background-color: #fff;">
                        
                        <select name="game_category_id" style="padding: 10px 15px; border-radius: 10px; border: 1px solid #ddd; font-weight: 600; outline: none; background-color: #fff;" onchange="this.form.submit()">
                            <option value="">Semua Game</option>
                            @foreach($games as $game)
                                <option value="{{ $game->id }}" {{ request('game_category_id') == $game->id ? 'selected' : '' }}>{{ $game->nama_game }}</option>
                            @endforeach
                        </select>
                        
                        <select name="level" style="padding: 10px 15px; border-radius: 10px; border: 1px solid #ddd; font-weight: 600; outline: none; background-color: #fff;" onchange="this.form.submit()">
                            <option value="">Semua Level</option>
                            @for($l = 1; $l <= $totalLevelMax; $l++)
                                <option value="{{ $l }}" {{ request('level') == $l ? 'selected' : '' }}>Level {{ $l }}</option>
                            @endfor
                        </select>
                        
                        <button type="submit" style="padding: 10px 20px; border-radius: 10px; border: none; background-color: #2b4c7e; color: #fff; font-weight: 700; cursor: pointer;">Cari</button>
                        
                        @if(request('game_category_id') || request('level') || request('search'))
                            <a href="{{ route('admin.questions') }}" class="btn-cancel" style="display: inline-flex; align-items: center; justify-content: center; text-decoration: none; padding: 10px 20px; border-radius: 10px; background-color: #FF4747; color: #FFF; font-weight: 700; border: none;">Reset</a>
                        @endif
                    </form>
                </div>

                <div style="overflow-x: auto;">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th style="width: 15%">Kategori Game</th>
                                <th style="width: 10%">Level</th>
                                <th style="width: 35%">Teks Soal</th>
                                <th style="width: 30%">Pilihan Jawaban</th>
                                <th style="width: 10%">Proses</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($questions) && $questions->count() > 0)
                                @foreach($questions as $question)
                                <tr>
                                    <td>{{ $question->gameCategory->nama_game ?? '-' }}</td>
                                    <td><span style="background-color: rgba(248, 203, 46, 0.2); color: #000; padding: 4px 8px; border-radius: 6px; font-weight: 800; font-size: 13px;">Lvl {{ $question->level }}</span></td>
                                    <td class="teks-soal-cell">{{ $question->teks_soal }}</td>
                                    <td>
                                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 6px 12px;">
                                            @foreach($question->answers as $index => $answer)
                                                <div style="display: flex; align-items: center; gap: 6px; font-size: 13px;">
                                                    <span style="font-weight: 800; color: {{ $answer->is_correct ? '#28a745' : '#888' }};">
                                                        {{ chr(65 + $index) }}.
                                                    </span>
                                                    <span style="color: {{ $answer->is_correct ? '#28a745' : '#555' }}; font-weight: {{ $answer->is_correct ? '800' : '600' }};">
                                                        {{ $answer->teks_jawaban }}
                                                    </span>
                                                    @if($answer->is_correct)
                                                        <i class="fa-solid fa-circle-check" style="color: #28a745; font-size: 12px;"></i>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td style="display: flex; gap: 5px; flex-wrap: wrap;">
                                        @php
                                            $ansA = $question->answers[0]->teks_jawaban ?? '';
                                            $ansB = $question->answers[1]->teks_jawaban ?? '';
                                            $ansC = $question->answers[2]->teks_jawaban ?? '';
                                            $ansD = $question->answers[3]->teks_jawaban ?? '';
                                            $kunci = 'A';
                                            foreach($question->answers as $idx => $ans) {
                                                if($ans->is_correct) {
                                                    $kunci = chr(65 + $idx);
                                                }
                                            }
                                            $questionData = [
                                                'id' => $question->id,
                                                'game_category_id' => $question->game_category_id,
                                                'level' => $question->level,
                                                'teks_soal' => $question->teks_soal,
                                                'jawaban_a' => $ansA,
                                                'jawaban_b' => $ansB,
                                                'jawaban_c' => $ansC,
                                                'jawaban_d' => $ansD,
                                                'kunci' => $kunci,
                                                'waktu' => $question->waktu,
                                                'pembahasan' => $question->pembahasan
                                            ];
                                        @endphp
                                        <button class="btn-edit" onclick='openEditModal(@json($questionData))' style="padding: 8px 15px; border-radius: 8px; font-size: 13px; display: inline-flex; align-items: center; gap: 5px; background-color: #28a745; color: white; border: none; cursor: pointer;"><i class="fa-solid fa-pen"></i> Edit</button>
                                        <button class="btn-delete" onclick="confirmDelete({{ $question->id }})" style="padding: 8px 15px; border-radius: 8px; font-size: 13px; display: inline-flex; align-items: center; gap: 5px; background-color: #FF4747; color: white; border: none; cursor: pointer;"><i class="fa-solid fa-trash"></i> Hapus</button>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 40px; font-weight: 700; color: #888;">
                                        <i class="fa-solid fa-circle-info" style="font-size: 24px; color: #F8CB2E; margin-bottom: 10px; display: block;"></i>
                                        Belum ada soal tersedia. Silakan impor file Excel/CSV di atas.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                @if(isset($questions) && $questions->count() > 0)
                    <div style="margin-top: 25px;">
                        {{ $questions->links('vendor.pagination.simple-default') ?? $questions->links() }}
                    </div>
                @endif
            </div>
        </main>
    </div>

    <!-- Hidden Delete Form -->
    <form id="deleteForm" method="POST" action="" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Hidden Delete All Form -->
    <form id="deleteAllForm" method="POST" action="{{ route('admin.questions.destroyAll') }}" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Edit Modal -->
    <div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div class="edit-modal-content">
            <h3 style="margin-bottom: 20px;">Edit Soal</h3>
            <form id="editForm" method="POST" action="">
                @csrf
                @method('PUT')
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Kategori Game</label>
                    <select name="game_category_id" id="edit_game_category_id" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;" required>
                        @foreach($games as $game)
                            <option value="{{ $game->id }}">{{ $game->nama_game }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Level</label>
                    <input type="number" name="level" id="edit_level" min="1" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;" required>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Teks Soal</label>
                    <textarea name="teks_soal" id="edit_teks_soal" rows="3" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;" required></textarea>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Waktu Pengerjaan (Detik)</label>
                    <input type="number" name="waktu" id="edit_waktu" min="1" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;" required>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Pembahasan</label>
                    <textarea name="pembahasan" id="edit_pembahasan" rows="3" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;" placeholder="Opsional"></textarea>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Jawaban A</label>
                    <input type="text" name="jawaban_a" id="edit_jawaban_a" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                </div>
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Jawaban B</label>
                    <input type="text" name="jawaban_b" id="edit_jawaban_b" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Jawaban C</label>
                    <input type="text" name="jawaban_c" id="edit_jawaban_c" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Jawaban D</label>
                    <input type="text" name="jawaban_d" id="edit_jawaban_d" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Kunci Jawaban</label>
                    <select name="kunci_jawaban" id="edit_kunci_jawaban" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;" required>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>
                </div>

                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" class="btn-cancel-modal" onclick="closeEditModal()" style="padding: 10px 20px; border-radius: 8px; border: none; background: #ddd; font-weight: 600; cursor: pointer;">Batal</button>
                    <button type="submit" style="padding: 10px 20px; border-radius: 8px; border: none; background: #F8CB2E; color: #000; font-weight: 600; cursor: pointer;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Handle Tampilan Berkas saat di-select
        const fileInput = document.getElementById('file_soal');
        const fileInfo = document.getElementById('fileInfo');
        const fileName = document.getElementById('fileName');

        fileInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                const file = e.target.files[0];
                fileName.innerText = file.name;
                fileInfo.style.display = 'block';
            } else {
                fileInfo.style.display = 'none';
            }
        });

        // Drag & Drop Handling
        const uploadArea = document.getElementById('uploadArea');

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, () => {
                uploadArea.style.borderColor = '#2b4c7e';
                uploadArea.style.backgroundColor = '#F4F7FC';
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, () => {
                uploadArea.style.borderColor = '#F8CB2E';
                uploadArea.style.backgroundColor = '#FFFDF5';
            }, false);
        });

        uploadArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            preventDefaults(e);
            let dt = e.dataTransfer;
            let files = dt.files;

            if (files.length > 0) {
                fileInput.files = files;
                fileName.innerText = files[0].name;
                fileInfo.style.display = 'block';
            }
        }

        // Edit Modal
        function openEditModal(data) {
            let form = document.getElementById('editForm');
            form.action = '/admin/questions/' + data.id;
            document.getElementById('edit_game_category_id').value = data.game_category_id;
            document.getElementById('edit_level').value = data.level;
            document.getElementById('edit_teks_soal').value = data.teks_soal;
            document.getElementById('edit_jawaban_a').value = data.jawaban_a;
            document.getElementById('edit_jawaban_b').value = data.jawaban_b;
            document.getElementById('edit_jawaban_c').value = data.jawaban_c;
            document.getElementById('edit_jawaban_d').value = data.jawaban_d;
            document.getElementById('edit_kunci_jawaban').value = data.kunci;
            document.getElementById('edit_waktu').value = data.waktu || 60;
            document.getElementById('edit_pembahasan').value = data.pembahasan || '';
            
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Hapus Konfirmasi
        function confirmDelete(id) {
            if (confirm('Apakah Anda yakin ingin menghapus soal ini beserta pilihan jawabannya?')) {
                let form = document.getElementById('deleteForm');
                form.action = '/admin/questions/' + id;
                form.submit();
            }
        }

        // Kosongkan Semua Soal
        function confirmDeleteAll() {
            if (confirm('⚠️ PERINGATAN!\n\nAnda akan MENGHAPUS SELURUH SOAL yang ada di bank soal.\nTotal: {{ $totalSoal ?? 0 }} soal akan dihapus secara permanen.\n\nTindakan ini TIDAK DAPAT dibatalkan!\n\nApakah Anda yakin?')) {
                if (confirm('Konfirmasi terakhir:\nKetuk OK sekali lagi untuk menghapus semua soal.')) {
                    document.getElementById('deleteAllForm').submit();
                }
            }
        }
    </script>
</body>
</html>
