<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UmaThink - Belajar &amp; Belajar</title>
    <meta name="description" content="Asah kemampuan Anda dengan berbagai macam permainan interaktif sambil mempelajari pengetahuan umum yang menarik.">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Navbar CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    {{-- Dashboard CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">

    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
</head>

<body>

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
                        <i class="fas fa-star"></i> Platform Edukasi Interaktif
                    </span>
                    <h1>Belajar dan Bermain di <br class="desktop-only"> Dunia Pengetahuan</h1>
                    <p>Asah kemampuan Anda dengan berbagai macam permainan interaktif sambil mempelajari pengetahuan umum yang menarik.</p>

                    <div class="button-wrapper">
                        <a href="{{ route('game.dashboard') }}" class="btn-primary" id="btn-mulai-bermain">
                            <i class="fas fa-gamepad"></i> Mulai Bermain!
                        </a>
                        <a href="{{ route('game.leaderboard') }}" class="btn-secondary" id="btn-pelajari">
                            <i class="fas fa-chart-bar"></i> Statisik Pengguna
                        </a>
                    </div>
                </div>

                <div class="hero-image">
                    <div class="hero-image-glow"></div>
                    <img src="{{ asset('assets/img/maskot.png') }}" alt="Umathink Maskot Terbang">
                </div>

            </div>
        </div>
    </section>

    {{-- LATEST NEWS SECTION --}}
    <section class="news-section" id="berita">
        <div class="news-slider-container">
            <div class="news-header">
                <span class="section-badge">Update Terbaru</span>
                <h2>Berita & Informasi</h2>
            </div>
            
            <div class="news-slider">
                <div class="news-card">
                    <img src="{{ asset('assets/img/berita1.png') }}" alt="Berita Terbaru 1">
                </div>
                <div class="news-card">
                    <img src="{{ asset('assets/img/berita2.png') }}" alt="Berita Terbaru 2">
                </div>
                <div class="news-card">
                    <img src="{{ asset('assets/img/berita3.png') }}" alt="Berita Terbaru 3">
                </div>
            </div>
        </div>
    </section>

    {{-- FITUR SECTION --}}
    <section class="features-section" id="fitur">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Kenapa UmaThink?</span>
                <h2>Fitur Unggulan Kami</h2>
                <p>Kami menyediakan berbagai fitur menarik untuk mendukung proses belajar Anda</p>
            </div>

            <div class="features-grid">
                <div class="feature-card" id="feature-interaktif">
                    <div class="feature-icon">
                        <i class="fas fa-puzzle-piece"></i>
                    </div>
                    <h3>Game Interaktif</h3>
                    <p>Berbagai permainan edukatif yang menyenangkan dan mengasah otak Anda.</p>
                </div>

                <div class="feature-card" id="feature-materi">
                    <div class="feature-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h3>Materi Lengkap</h3>
                    <p>Konten pengetahuan umum yang dikemas secara menarik dan mudah dipahami.</p>
                </div>

                <div class="feature-card" id="feature-progress">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Pantau Progress</h3>
                    <p>Lacak kemajuan belajar Anda dengan statistik dan pencapaian detail.</p>
                </div>

                <div class="feature-card" id="feature-leaderboard">
                    <div class="feature-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3>Leaderboard</h3>
                    <p>Bersaing dengan pemain lain dan raih posisi tertinggi di papan peringkat.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- STATS SECTION --}}
    <section class="stats-section" id="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card" id="stat-pengguna">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <h3 class="stat-number">500+</h3>
                    <p>Pengguna Aktif</p>
                </div>
                <div class="stat-card" id="stat-game">
                    <div class="stat-icon"><i class="fas fa-gamepad"></i></div>
                    <h3 class="stat-number">50+</h3>
                    <p>Game Tersedia</p>
                </div>
                <div class="stat-card" id="stat-soal">
                    <div class="stat-icon"><i class="fas fa-question-circle"></i></div>
                    <h3 class="stat-number">1000+</h3>
                    <p>Bank Soal</p>
                </div>
                <div class="stat-card" id="stat-rating">
                    <div class="stat-icon"><i class="fas fa-star"></i></div>
                    <h3 class="stat-number">4.8</h3>
                    <p>Rating Pengguna</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA SECTION --}}
    <section class="cta-section" id="cta">
        <div class="container">
            <div class="cta-content">
                <h2>Siap Untuk Mulai Belajar?</h2>
                <p>Bergabung sekarang dan rasakan pengalaman belajar yang seru dan interaktif!</p>
                <a href="{{ route('game.dashboard') }}" class="btn-primary btn-lg" id="btn-cta-bermain">
                    <i class="fas fa-rocket"></i> Mulai Sekarang
                </a>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slider = document.querySelector('.news-slider');
            const cards = document.querySelectorAll('.news-card');
            
            if (!slider || cards.length === 0) return;

            let currentIndex = 0;
            const totalCards = cards.length;
            let autoScrollInterval;

            function scrollToNextCard() {
                currentIndex = (currentIndex + 1) % totalCards;
                const targetCard = cards[currentIndex];
                
                slider.scrollTo({
                    left: targetCard.offsetLeft - slider.offsetLeft,
                    behavior: 'smooth'
                });
            }

            function startAutoScroll() {
                autoScrollInterval = setInterval(scrollToNextCard, 5000); // 5 seconds
            }

            function stopAutoScroll() {
                clearInterval(autoScrollInterval);
            }

            // Start auto scroll
            startAutoScroll();

            // Pause auto scroll when user interacts with the slider
            slider.addEventListener('mouseenter', stopAutoScroll);
            slider.addEventListener('mouseleave', startAutoScroll);
            slider.addEventListener('touchstart', stopAutoScroll);
            slider.addEventListener('touchend', startAutoScroll);
        });
    </script>
</body>

</html>