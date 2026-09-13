@extends('layouts.dashboard')

@section('title', 'Profil Saya')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">

<div class="profile-container">

    <!-- FOTO + NAMA -->
    <div class="profile-header">

        <img src="{{ auth()->user()->photo ?: (auth()->user()->avatar ?: asset('assets/img/default-user.png')) }}"
            class="profile-img">

        <div>
            <h2>{{ auth()->user()->namalengkap }}</h2>
            <p>Halo, {{ auth()->user()->username }}</p>
            <p class="email">{{ auth()->user()->email }}</p>

            <div class="profile-actions">
                <button type="button" class="btn-edit" id="btnOpenEditModal">
                    Ubah Profile
                </button>
                <a href="/logout" class="btn-logout" style="display:inline-block; text-align:center; text-decoration:none;">
                    Log Out
                </a>
            </div>
        </div>

    </div>

    <!-- PENCAPAIAN -->
    <h3>Pencapaian:</h3>

    <div class="achievement-box">

        <div class="card">
            <img src="{{ asset('assets/img/trophy.png') }}">
            <div>
                <h2>{{ auth()->user()->total_poin }}</h2>
                <p>Total Poin</p>
            </div>
        </div>

        <div class="card">
            <img src="{{ asset('assets/img/star.png') }}">
            <div>
                <h2>{{ auth()->user()->peringkat }}</h2>
                <p>Peringkat</p>
            </div>
        </div>

        @php
            $currentLiga = auth()->user()->liga ?? 'Bronze';
            $currentLigaLower = strtolower($currentLiga);
        @endphp
        <div class="card">
            <img src="{{ asset('assets/img/' . $currentLigaLower . '.png') }}" onerror="this.src='{{ asset('assets/img/medal.png') }}'">
            <div>
                <h2>{{ auth()->user()->liga }}</h2>
                <p>Liga</p>
            </div>
        </div>

    </div>

    <!-- RIWAYAT PENCAPAIAN TERTINGGI -->
    <h3 style="margin-top: 30px;">Riwayat Pencapaian Tertinggi:</h3>

    <div class="achievement-box-wide">
        <div class="card wide-card">

            @php
                $user = auth()->user();
                $currentPoin = $user->total_poin ?? 0;
                $currentPeringkat = $user->peringkat;
                $currentLiga = $user->liga ?? 'Bronze';

                $highestPoin = $user->highest_poin ?? 0;
                $highestPeringkat = $user->highest_peringkat;
                $highestLiga = $user->highest_liga ?? 'Bronze';

                // Jika pencapaian saat ini memecahkan rekor (atau sama dengan rekor)
                if ($currentPoin >= $highestPoin) {
                    $highestPoin = $currentPoin;
                    $highestLiga = $currentLiga;
                    
                    if ($highestPeringkat === null || (is_numeric($currentPeringkat) && $currentPeringkat <= $highestPeringkat)) {
                        $highestPeringkat = $currentPeringkat;
                    }
                }
                
                // Jika peringkat tertinggi masih kosong, gunakan peringkat saat ini
                if ($highestPeringkat === null && is_numeric($currentPeringkat)) {
                    $highestPeringkat = $currentPeringkat;
                }

                $highestLigaLower = strtolower($highestLiga);
            @endphp
            <div class="stat-item">
                <img src="{{ asset('assets/img/' . $highestLigaLower . '.png') }}" onerror="this.src='{{ asset('assets/img/medal.png') }}'">
                <div>
                    <h2>{{ $highestLiga }}</h2>
                    <p>Liga Tertinggi</p>
                </div>
            </div>

            <div class="divider"></div>

            <div class="stat-item">
                <img src="{{ asset('assets/img/star.png') }}">
                <div>
                    <h2>{{ $highestPeringkat ?? '-' }}</h2>
                    <p>Peringkat Tertinggi</p>
                </div>
            </div>

            <div class="divider"></div>

            <div class="stat-item">
                <img src="{{ asset('assets/img/trophy.png') }}">
                <div>
                    <h2>{{ $highestPoin }}</h2>
                    <p>Poin Tertinggi</p>
                </div>
            </div>

        </div>
    </div>

</div>

<!-- MODAL UBAH PROFILE -->
<div id="editProfileModal" class="modal-overlay">
    <div class="modal-content">
        <span class="close-modal" id="btnCloseEditModal">&times;</span>
        <h3>Ubah Profile</h3>
        
        <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="{{ auth()->user()->username }}" required>
            </div>

            <div class="form-group">
                <label for="namalengkap">Nama Lengkap</label>
                <input type="text" id="namalengkap" name="namalengkap" value="{{ auth()->user()->namalengkap }}" required>
            </div>

            <div class="form-group">
                <label>Foto Profile</label>
                <div class="custom-file-upload">
                    <label for="photo" class="btn-choose-file">📁 Pilih File</label>
                    <input type="file" id="photo" name="photo" accept="image/*">
                    <span class="file-name" id="fileName">Belum ada file dipilih</span>
                </div>
                <small>Format: JPG, PNG. Maks 2MB. Kosongkan jika tidak ingin mengubah foto.</small>
            </div>

            <button type="submit" class="btn-save">Simpan Perubahan</button>
        </form>
    </div>
</div>

<style>
/* CSS UNTUK MODAL */
.modal-overlay {
    display: none; 
    position: fixed; 
    z-index: 1000; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    background-color: rgba(0,0,0,0.5); 
    align-items: center; 
    justify-content: center;
}
.modal-overlay.active {
    display: flex;
}
.modal-content {
    background-color: #fff;
    padding: 30px;
    border-radius: 15px;
    width: 90%;
    max-width: 400px;
    position: relative;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}
.modal-content h3 {
    margin-bottom: 20px;
    color: #333;
}
.close-modal {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 24px;
    cursor: pointer;
    color: #888;
}
.close-modal:hover {
    color: #333;
}
.form-group {
    margin-bottom: 15px;
}
.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #555;
    font-size: 14px;
}
.form-group input[type="text"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
}
.form-group input[type="file"] {
    display: none;
}
.custom-file-upload {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 4px;
}
.btn-choose-file {
    display: inline-block !important;
    padding: 8px 16px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff !important;
    border-radius: 8px;
    font-size: 13px !important;
    font-weight: 600 !important;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
    flex-shrink: 0;
}
.btn-choose-file:hover {
    background: linear-gradient(135deg, #5a6fd6, #6a4299);
    transform: translateY(-1px);
    box-shadow: 0 3px 10px rgba(102, 126, 234, 0.4);
}
.file-name {
    font-size: 13px;
    color: #888;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 200px;
}
.file-name.has-file {
    color: #333;
    font-weight: 500;
}
.form-group small {
    display: block;
    margin-top: 5px;
    color: #888;
    font-size: 12px;
}
.btn-save {
    width: 100%;
    padding: 12px;
    background-color: #F8CB2E;
    color: #000;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    cursor: pointer;
    font-size: 16px;
    margin-top: 10px;
    transition: background 0.3s;
}
.btn-save:hover {
    background-color: #e6b825;
}

/* Dark Mode Overrides untuk Modal */
body.dark-mode .modal-content {
    background-color: #1a1a2e;
    box-shadow: 0 5px 15px rgba(0,0,0,0.5);
    border: 1px solid #2d2d44;
}
body.dark-mode .modal-content h3 {
    color: #f8f9fa;
}
body.dark-mode .close-modal {
    color: #a0a0b8;
}
body.dark-mode .close-modal:hover {
    color: #f8f9fa;
}
body.dark-mode .form-group label {
    color: #d1d1e0;
}
body.dark-mode .form-group input[type="text"] {
    background-color: #252542;
    color: #f8f9fa;
    border: 1px solid #3b3b5c;
}
body.dark-mode .file-name {
    color: #a0a0b8;
}
body.dark-mode .file-name.has-file {
    color: #f8f9fa;
}
</style>

<script>
    const modal = document.getElementById('editProfileModal');
    const btnOpen = document.getElementById('btnOpenEditModal');
    const btnClose = document.getElementById('btnCloseEditModal');

    btnOpen.addEventListener('click', () => {
        modal.classList.add('active');
    });

    btnClose.addEventListener('click', () => {
        modal.classList.remove('active');
    });

    // Tutup jika klik area luar modal
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('active');
        }
    });

    // Tampilkan nama file yang dipilih
    document.getElementById('photo').addEventListener('change', function() {
        const fileNameEl = document.getElementById('fileName');
        if (this.files && this.files.length > 0) {
            fileNameEl.textContent = this.files[0].name;
            fileNameEl.classList.add('has-file');
        } else {
            fileNameEl.textContent = 'Belum ada file dipilih';
            fileNameEl.classList.remove('has-file');
        }
    });
</script>

@endsection