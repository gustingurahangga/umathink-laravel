<nav class="navbar-custom">
    <div class="container-navbar">

        <!-- Logo -->
        <a href="/customer" class="logo">
            <div class="logo-icon">U</div>
            <span>UmaThink</span>
        </a>

        <!-- Menu -->
        <div class="menu-right">

            <a href="#"
                class="menu-link {{ request()->routeIs('home') ? 'active' : '' }}">
                HOME
            </a>

            <a href="#"
                class="menu-link {{ request()->routeIs('about') ? 'active' : '' }}">
                ABOUT
            </a>

            <!-- Foto Profil -->
            @auth
            <a href="/customer/profile">
                <img src="{{ auth()->user()->photo ?? asset('assets/img/default-user.png') }}"
                    class="navbar-profile-img">
            </a>
        </div>
    </div>
    @endauth

    @guest
    <a href="#" class="login-btn">Login</a>
    @endguest

    </div>
    </div>
</nav>

<style>
    /* NAVBAR */
    .navbar-profile-img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .navbar-custom {
        background: #f5f5f5;
        border-bottom: 3px solid #f5b000;
        padding: 10px 0;
        font-family: 'Poppins', sans-serif;
    }

    .container-navbar {
        width: 90%;
        margin: auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* LOGO */
    .logo {
        display: flex;
        align-items: center;
        text-decoration: none;
        font-weight: 600;
        font-size: 18px;
        color: #333;
    }

    .logo-icon {
        background: #f5b000;
        color: white;
        padding: 5px 10px;
        border-radius: 6px;
        margin-right: 8px;
        font-weight: bold;
    }

    /* MENU */
    .menu-right {
        display: flex;
        align-items: center;
        gap: 25px;
    }

    .menu-link {
        text-decoration: none;
        color: #333;
        font-weight: 600;
        font-size: 14px;
    }

    .menu-link:hover,
    .menu-link.active {
        color: #f5b000;
    }

    /* PROFILE */
    .profile-img {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        cursor: pointer;
    }

    /* DROPDOWN */
    .profile-dropdown {
        position: relative;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        top: 50px;
        background: white;
        min-width: 140px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    .dropdown-content a,
    .dropdown-content button {
        display: block;
        width: 100%;
        padding: 10px;
        text-decoration: none;
        border: none;
        background: none;
        text-align: left;
        cursor: pointer;
        font-size: 14px;
    }

    .dropdown-content a:hover,
    .dropdown-content button:hover {
        background: #f5f5f5;
    }

    .login-btn {
        background: #f5b000;
        padding: 6px 14px;
        border-radius: 6px;
        color: white;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
    }
</style>

<script>
    function toggleDropdown() {
        document.getElementById("dropdownMenu").classList.toggle("show");
    }

    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById("dropdownMenu");
        if (!event.target.closest('.profile-dropdown')) {
            dropdown.classList.remove("show");
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        const style = document.createElement('style');
        style.innerHTML = `.show { display:block; }`;
        document.head.appendChild(style);
    });
</script>