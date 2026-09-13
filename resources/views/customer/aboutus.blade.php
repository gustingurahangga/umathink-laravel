<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UmaThink - Belajar &amp; Bermain</title>
    <meta name="description" content="Asah kemampuan Anda dengan berbagai macam permainan interaktif sambil mempelajari pengetahuan umum yang menarik.">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Navbar & Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/aboutus.css') }}">

    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
</head>

<body class="{{ (isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'dark') ? 'dark-mode' : '' }}">
    <script>
        // Check localStorage for theme as well
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
        }
    </script>

    {{-- NAVBAR --}}
    @include('layouts.app')

    {{-- HERO SECTION --}}
    <section class="hero-section" id="hero">
        <div class="hero-bg-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <span class="hero-badge">
                        <i class="fas fa-star"></i> Tentang Kami 
                    </span>
                    <h1>Kami Percaya Belajar Harus Seru <br class="desktop-only"> Bermakna, Dan Menginspirasi</h1>
                    <p>Dari ide kecil di kampus, kami tumbuh jadi ruang belajar interaktif untuk semua pejuang mimpi.</p>
                </div>
                <div class="hero-image">
                    <div class="hero-image-glow"></div>
                    <img src="{{ asset('assets/img/logo-about.png') }}" alt="Umathink Maskot Terbang">
                </div>
            </div>
        </div>
    </section>

    {{-- FITUR SECTION --}}
    <section class="features-section" id="fitur">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Our Values</span>
                <h2>Prinsip Kami dalam Berkarya dan Belajar</h2>
            </div>

            <div class="features-grid">
                <div class="feature-card" id="feature-interaktif">
                    <div class="feature-icon"><i class="fas fa-puzzle-piece"></i></div>
                    <h3>Kreatif dalam Belajar</h3>
                    <p>Kami percaya setiap orang punya cara unik untuk memahami dunia.</p>
                </div>
                <div class="feature-card" id="feature-materi">
                    <div class="feature-icon"><i class="fas fa-book-open"></i></div>
                    <h3>Komunitas Pendukung</h3>
                    <p>Belajar nggak harus sendirian. Kami hadir sebagai ruang yang saling mendukung.</p>
                </div>
                <div class="feature-card" id="feature-progress">
                    <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                    <h3>Pertumbuhan Tanpa Batas</h3>
                    <p>Setiap langkah kecil adalah bagian dari perjalanan besar, tanpa pernah berhenti belajar.</p>
                </div>
                <div class="feature-card" id="feature-leaderboard">
                    <div class="feature-icon"><i class="fas fa-trophy"></i></div>
                    <h3>Belajar Sambil Bermain</h3>
                    <p>Kami ubah proses belajar jadi pengalaman yang interaktif, menyenangkan penuh kejutan.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ==========================================
         TAMBAHAN: VISION & MISSION SECTION 
         ========================================== --}}
    <section class="vision-mission-section">
        <div class="container">
            <div class="vm-row">
                <div class="vm-title">Our <span class="text-yellow">Vision</span></div>
                <div class="vm-text">
                    Membuat proses belajar jadi pengalaman yang seru, interaktif, dan mudah diakses oleh siapa pun. Kami ingin setiap pengguna merasa didukung untuk tumbuh, menemukan potensi terbaiknya, dan menghadapi setiap tantangan dengan semangat positif.
                </div>
            </div>
            
            <div class="vm-row">
                <div class="vm-title">Our <span class="text-yellow">Mission</span></div>
                <div class="vm-text">
                    Menjadi ruang digital pembelajaran yang memberdayakan generasi muda untuk terus berkembang — bukan hanya cerdas dalam pengetahuan, tapi juga tangguh dalam perjalanan menuju masa depan.
                </div>
            </div>
        </div>
    </section>

    {{-- ==========================================
         TAMBAHAN: MEET THE TEAM SECTION 
         ========================================== --}}
    <section class="team-section">
        <div class="container">
            <div class="team-layout">
                
                <div class="team-cards">
                    <div class="team-card">
                        <img src="{{ asset('assets/img/team-angga.png') }}" alt="Angga Wibawa" class="team-avatar bg-pink">
                        <div class="team-info">
                            <h4>Angga Wibawa</h4>
                            <p>IT Support</p>
                        </div>
                    </div>
                    <div class="team-card">
                        <img src="{{ asset('assets/img/team-peter.png') }}" alt="Peter Parker" class="team-avatar bg-blue">
                        <div class="team-info">
                            <h4>Peter Parker</h4>
                            <p>Founder Umathink</p>
                        </div>
                    </div>
                    <div class="team-card">
                        <img src="{{ asset('assets/img/team-tom.png') }}" alt="Tom Holland" class="team-avatar bg-blue">
                        <div class="team-info">
                            <h4>Tom Holland</h4>
                            <p>IT Support</p>
                        </div>
                    </div>
                </div>

                <div class="team-text">
                    <h2>Meet The<br>Team<br><span class="text-yellow">Umathink</span></h2>
                </div>

            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="footer" id="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <div class="footer-logo">
                        <div class="logo-icon">U</div>
                        <span>UmaThink</span>
                    </div>
                    <p>Platform edukasi interaktif yang membuat belajar jadi menyenangkan.</p>
                </div>
                <div class="footer-links">
                    <h4>Navigasi</h4>
                    <a href="#">Home</a>
                    <a href="#">About</a>
                    <a href="#">Games</a>
                </div>
                <div class="footer-links">
                    <h4>Kontak</h4>
                    <a href="#"><i class="fas fa-envelope"></i>admin@rumahpenalaran.com</a>
                    <a href="https://www.instagram.com/rumahpenalaran/"><i class="fab fa-instagram"></i> @rumahpenalaran</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} UmaThink. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>