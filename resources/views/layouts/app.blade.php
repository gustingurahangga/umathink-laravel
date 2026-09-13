<nav class="navbar">
    <div class="navbar-container">

        <div class="navbar-logo">
            <a href="/">
                <img src="{{ asset('assets/img/logo-umathink.png') }}" alt="Umathink Logo">
            </a>
        </div>

        {{-- Dark Mode Toggle --}}
        <button id="themeToggle" class="theme-toggle" aria-label="Toggle dark mode">
            <i class="fas fa-moon"></i>
        </button>

        {{-- Tombol Hamburger (hanya muncul di mobile) --}}
        <button class="navbar-toggle" id="navbarToggle" aria-label="Toggle menu">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>

        <div class="navbar-menu" id="navbarMenu">
            @if(!Auth::check() || Auth::user()->role !== 'admin')
                <a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Beranda</a>
                <a href="/tentang-kami" class="nav-link {{ request()->is('tentang-kami') ? 'active' : '' }}">Tentang Kami</a>
            @endif
            
            @auth
                @if(Auth::user()->role !== 'admin')
                    <a href="/customer/profile" class="nav-link {{ request()->is('customer/profile') ? 'active' : '' }}">Pengaturan</a>
                @endif
                <a href="/logout" class="btn-logout">Logout</a>
            @else
                <a href="/login" class="btn-login">Login</a>
            @endauth
        </div>
    </div>
</nav>

<script>
    const navbarToggle = document.getElementById('navbarToggle');
    const navbarMenu = document.getElementById('navbarMenu');
    const themeToggle = document.getElementById('themeToggle');
    const body = document.body;

    // Hamburger Menu Toggle
    navbarToggle.addEventListener('click', function () {
        this.classList.toggle('active');
        navbarMenu.classList.toggle('open');
    });

    // Dark Mode Toggle Logic
    const currentTheme = localStorage.getItem('theme');
    if (currentTheme === 'dark') {
        body.classList.add('dark-mode');
        themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
    }

    themeToggle.addEventListener('click', () => {
        body.classList.toggle('dark-mode');
        let theme = 'light';
        if (body.classList.contains('dark-mode')) {
            theme = 'dark';
            themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
        } else {
            themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
        }
        localStorage.setItem('theme', theme);
    });
</script>