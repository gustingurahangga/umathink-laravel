<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        UmaThink - Belajar &amp; Bermain
    </title>


    <!-- FONT -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap"
        rel="stylesheet"
    >


    <!-- FONT AWESOME -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    >


    <!-- CSS UTAMA -->
    <link
        rel="stylesheet"
        href="{{ asset('assets/css/style.css') }}"
    >


    <!-- CSS LEADERBOARD -->
    <link
        rel="stylesheet"
        href="{{ asset('assets/css/leaderboard.css') }}"
    >


    <!-- FAVICON -->
    <link
        rel="icon"
        type="image/png"
        href="{{ asset('assets/img/logo.png') }}"
    >

</head>


<body>


    @include('layouts.app')


    <div class="app-container">


        <!-- =====================================================
             SIDEBAR
        ====================================================== -->

        <aside class="sidebar">


            <div class="logo-top">

                <img
                    src="{{ asset('assets/img/umathink_logo_text.png') }}"
                    alt="UmaThink"
                    class="sidebar-logo"
                >

            </div>


            <nav class="side-nav">


                <!-- DASHBOARD -->

                <a
                    href="{{ route('game.dashboard') }}"
                    class="nav-item"
                >

                    <i class="fa-solid fa-book-open"></i>

                </a>


                <!-- LEADERBOARD -->

                <a
                    href="{{ route('game.leaderboard') }}"
                    class="nav-item active"
                >

                    <i class="fa-solid fa-chart-simple"></i>

                </a>


                <!-- PROFILE -->

                <a
                    href="{{ route('customer.profile') }}"
                    class="nav-item"
                >

                    <i class="fa-solid fa-user"></i>

                </a>


            </nav>


        </aside>



        <!-- =====================================================
             MAIN CONTENT
        ====================================================== -->

        <main class="main-content">


            <!-- =================================================
                 STATISTIK USER
            ================================================== -->

            <section class="stats-row">


                <!-- =============================================
                     PERINGKAT
                ============================================== -->

                <div class="stat-card">


                    <div class="icon-circle yellow">


                        <img
                            src="{{ asset('assets/img/star.png') }}"
                            alt="Peringkat"
                            style="
                                width:28px;
                                height:28px;
                                object-fit:contain;
                            "
                        >


                    </div>


                    <div class="stat-info">


                        <span class="stat-value">

                            {{ auth()->user()->peringkat }}

                        </span>


                        <span class="stat-label">

                            Peringkat

                        </span>


                    </div>


                </div>



                <!-- =============================================
                     TOTAL BINTANG
                     MENGGUNAKAN TROPHY
                ============================================== -->

                <div class="stat-card">


                    <div class="icon-circle orange">


                        <img
                            src="{{ asset('assets/img/trophy.png') }}"
                            alt="Total Bintang"
                            style="
                                width:28px;
                                height:28px;
                                object-fit:contain;
                            "
                        >


                    </div>


                    <div class="stat-info">


                        <span class="stat-value">

                            {{ auth()->user()->total_bintang }}

                        </span>


                        <span class="stat-label">

                            Total Bintang

                        </span>


                    </div>


                </div>



                <!-- =============================================
                     KLASTER
                     TAMPILKAN NAMA SINGKAT
                ============================================== -->

                @php

                    $currentUser =
                        auth()->user();

                    $currentKlaster =
                        $currentUser->klaster;

                    /*
                     * Ambil nama klaster.
                     *
                     * Contoh:
                     * Klaster Amatir
                     */
                    $currentKlasterNama =
                        $currentKlaster['nama']
                        ?? 'Klaster Amatir';

                    /*
                     * Hapus kata "Klaster "
                     * sehingga menjadi:
                     *
                     * Amatir
                     */
                    $currentKlasterSingkat =
                        preg_replace(
                            '/^Klaster\s+/i',
                            '',
                            $currentKlasterNama
                        );

                @endphp


                <div class="stat-card">


                    <div class="icon-circle green">


                        <img
                            src="{{ asset($currentKlaster['logo'] ?? 'assets/img/medal.png') }}"
                            alt="{{ $currentKlasterSingkat }}"
                            style="
                                width:32px;
                                height:32px;
                                object-fit:contain;
                            "
                            onerror="
                                this.src='{{ asset('assets/img/medal.png') }}'
                            "
                        >


                    </div>


                    <div class="stat-info">


                        <span
                            class="stat-value"
                            style="font-size:16px;"
                        >

                            {{ $currentKlasterSingkat }}

                        </span>


                        <span class="stat-label">

                            Klaster

                        </span>


                    </div>


                </div>


            </section>



            <!-- =================================================
                 LEADERBOARD
            ================================================== -->

            <section class="leaderboard-container">


                <div class="leaderboard-axis">


                    <!-- =========================================
                         HEADER
                    ========================================== -->

                    <div class="table-header">


                        <span
                            class="col-user"
                            style="
                                text-align:left;
                                padding-left:20px;
                            "
                        >

                            Username

                        </span>


                        <span class="col-points">

                            Bintang

                        </span>


                        <span class="col-league">

                            Klaster

                        </span>


                    </div>



                    <!-- =========================================
                         DAFTAR USER
                    ========================================== -->

                    <div class="rank-list">


                        @php

                            $isCurrentUserInTop10 =
                                false;

                        @endphp



                        @forelse($users as $index => $user)


                            @if(
                                auth()->check() &&
                                auth()->id() === $user->id
                            )

                                @php

                                    $isCurrentUserInTop10 =
                                        true;

                                @endphp

                            @endif



                            @php

                                /*
                                 * Total bintang user
                                 */
                                $totalBintang =
                                    (int) (
                                        $user->total_bintang
                                        ?? 0
                                    );


                                /*
                                 * Data klaster
                                 */
                                $klaster =
                                    $user->klaster;


                                /*
                                 * Nama klaster lengkap
                                 */
                                $klasterNama =
                                    $klaster['nama']
                                    ?? 'Klaster Amatir';


                                /*
                                 * Hapus kata "Klaster "
                                 *
                                 * Klaster Amatir
                                 *        ↓
                                 * Amatir
                                 */
                                $klasterSingkat =
                                    preg_replace(
                                        '/^Klaster\s+/i',
                                        '',
                                        $klasterNama
                                    );


                                /*
                                 * Avatar
                                 */
                                $avatar =
                                    $user->photo
                                    ?: (
                                        $user->avatar
                                        ?: asset(
                                            'assets/img/default-user.png'
                                        )
                                    );

                            @endphp



                            <!-- =================================
                                 BARIS USER
                            ================================== -->

                            <div
                                class="
                                    rank-item
                                    {{
                                        auth()->check()
                                        &&
                                        auth()->id() === $user->id
                                            ? 'current-user-row'
                                            : ''
                                    }}
                                "
                            >


                                <!-- NOMOR -->

                                <span class="rank-number">

                                    {{
                                        sprintf(
                                            '%02d',
                                            $index + 1
                                        )
                                    }}.

                                </span>



                                <!-- =================================
                                     CONTENT
                                ================================== -->

                                <div
                                    class="rank-content"
                                    style="
                                        display:flex;
                                        align-items:center;
                                        width:100%;
                                    "
                                >


                                    <!-- =========================
                                         USERNAME
                                    ========================== -->

                                    <span
                                        class="col-user"
                                        style="
                                            flex:2;
                                            text-align:left;
                                            font-weight:600;
                                            font-size:18px;
                                            display:flex;
                                            align-items:center;
                                            justify-content:flex-start;
                                            gap:15px;
                                            padding-left:20px;
                                        "
                                    >


                                        <img
                                            src="{{ $avatar }}"
                                            alt="{{ $user->username }}"
                                            class="leaderboard-avatar"
                                        >


                                        {{ $user->username }}


                                    </span>



                                    <!-- =========================
                                         BINTANG
                                         MENGGUNAKAN TROPHY
                                    ========================== -->

                                    <span
                                        class="col-points"
                                        style="
                                            flex:1;
                                            text-align:center;
                                            font-weight:700;
                                            font-size:18px;
                                            display:flex;
                                            align-items:center;
                                            justify-content:center;
                                            gap:6px;
                                        "
                                    >


                                        <img
                                            src="{{ asset('assets/img/trophy.png') }}"
                                            alt="Bintang"
                                            style="
                                                width:22px;
                                                height:22px;
                                                object-fit:contain;
                                            "
                                        >


                                        {{ $totalBintang }}


                                    </span>



                                    <!-- =========================
                                         KLASTER
                                         NAMA SINGKAT
                                    ========================== -->

                                    <span
                                        class="col-league"
                                        style="
                                            flex:1.5;
                                            display:flex;
                                            align-items:center;
                                            justify-content:center;
                                            gap:10px;
                                            font-weight:600;
                                            font-size:16px;
                                        "
                                    >


                                        <!-- NAMA KLASTER -->

                                        <span>

                                            {{ $klasterSingkat }}

                                        </span>



                                        <!-- LOGO KLASTER -->

                                        <div
                                            class="
                                                league-wrapper
                                                cluster-wrapper
                                                cluster-{{
                                                    strtolower(
                                                        str_replace(
                                                            ' ',
                                                            '-',
                                                            $klasterSingkat
                                                        )
                                                    )
                                                }}
                                            "
                                        >


                                            <img
                                                src="{{ asset($klaster['logo'] ?? 'assets/img/medal.png') }}"
                                                class="league-icon"
                                                alt="{{ $klasterSingkat }}"
                                                onerror="
                                                    this.src='{{ asset('assets/img/medal.png') }}'
                                                "
                                            >


                                        </div>


                                    </span>


                                </div>


                            </div>


                        @empty


                            <!-- =================================
                                 BELUM ADA USER
                            ================================== -->

                            <div class="rank-item">


                                <span class="rank-number">

                                    -

                                </span>


                                <div
                                    class="rank-content"
                                    style="
                                        display:flex;
                                        align-items:center;
                                        width:100%;
                                    "
                                >


                                    <span
                                        style="
                                            flex:1;
                                            text-align:center;
                                            color:#888;
                                        "
                                    >

                                        Belum ada pengguna.

                                    </span>


                                </div>


                            </div>


                        @endforelse



                        <!-- =========================================
                             CURRENT USER DI LUAR TOP 10
                        ========================================== -->

                        @if(
                            auth()->check() &&
                            auth()->user()->role === 'customer' &&
                            !$isCurrentUserInTop10
                        )


                            @php

                                $currentUser =
                                    auth()->user();


                                $currentKlaster =
                                    $currentUser->klaster;


                                /*
                                 * Nama klaster lengkap
                                 */
                                $currentKlasterNama =
                                    $currentKlaster['nama']
                                    ?? 'Klaster Amatir';


                                /*
                                 * Nama singkat
                                 */
                                $currentKlasterSingkat =
                                    preg_replace(
                                        '/^Klaster\s+/i',
                                        '',
                                        $currentKlasterNama
                                    );


                                /*
                                 * Avatar user
                                 */
                                $currentAvatar =
                                    $currentUser->photo
                                    ?: (
                                        $currentUser->avatar
                                        ?: asset(
                                            'assets/img/default-user.png'
                                        )
                                    );

                            @endphp



                            <div
                                style="
                                    margin-top:20px;
                                    padding-top:20px;
                                    border-top:
                                        2px dashed
                                        rgba(0,0,0,0.1);
                                "
                            >


                                <div
                                    class="
                                        rank-item
                                        current-user-row
                                    "
                                >


                                    <!-- NOMOR -->

                                    <span
                                        class="rank-number"
                                    >

                                        {{
                                            sprintf(
                                                '%02d',
                                                $currentUser->peringkat
                                            )
                                        }}.

                                    </span>



                                    <div
                                        class="rank-content"
                                        style="
                                            display:flex;
                                            align-items:center;
                                            width:100%;
                                        "
                                    >


                                        <!-- =====================
                                             USERNAME
                                        ====================== -->

                                        <span
                                            class="col-user"
                                            style="
                                                flex:2;
                                                text-align:left;
                                                font-weight:600;
                                                font-size:18px;
                                                display:flex;
                                                align-items:center;
                                                justify-content:flex-start;
                                                gap:15px;
                                                padding-left:20px;
                                            "
                                        >


                                            <img
                                                src="{{ $currentAvatar }}"
                                                alt="{{ $currentUser->username }}"
                                                class="leaderboard-avatar"
                                            >


                                            {{ $currentUser->username }}


                                        </span>



                                        <!-- =====================
                                             BINTANG
                                             MENGGUNAKAN TROPHY
                                        ====================== -->

                                        <span
                                            class="col-points"
                                            style="
                                                flex:1;
                                                text-align:center;
                                                font-weight:700;
                                                font-size:18px;
                                                display:flex;
                                                align-items:center;
                                                justify-content:center;
                                                gap:6px;
                                            "
                                        >


                                            <img
                                                src="{{ asset('assets/img/trophy.png') }}"
                                                alt="Bintang"
                                                style="
                                                    width:22px;
                                                    height:22px;
                                                    object-fit:contain;
                                                "
                                            >


                                            {{ $currentUser->total_bintang }}


                                        </span>



                                        <!-- =====================
                                             KLASTER
                                             NAMA SINGKAT
                                        ====================== -->

                                        <span
                                            class="col-league"
                                            style="
                                                flex:1.5;
                                                display:flex;
                                                align-items:center;
                                                justify-content:center;
                                                gap:10px;
                                                font-weight:600;
                                                font-size:16px;
                                            "
                                        >


                                            <span>

                                                {{ $currentKlasterSingkat }}

                                            </span>



                                            <!-- LOGO KLASTER -->

                                            <div
                                                class="
                                                    league-wrapper
                                                    cluster-wrapper
                                                "
                                            >


                                                <img
                                                    src="{{ asset($currentKlaster['logo'] ?? 'assets/img/medal.png') }}"
                                                    class="league-icon"
                                                    alt="{{ $currentKlasterSingkat }}"
                                                    onerror="
                                                        this.src='{{ asset('assets/img/medal.png') }}'
                                                    "
                                                >


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



    <!-- =========================================================
         TAMBAHAN CSS KHUSUS LEADERBOARD
    ========================================================== -->

    <style>


        /* =========================================
           AVATAR
        ========================================== */

        .leaderboard-avatar {

            width: 36px;

            height: 36px;

            border-radius: 50%;

            object-fit: cover;

            border:
                2px solid
                #e0e0e0;

            flex-shrink: 0;

            box-shadow:
                0 2px 6px
                rgba(0,0,0,0.1);

        }


        .current-user-row
        .leaderboard-avatar {

            border-color:
                #F8CB2E;

        }



        /* =========================================
           LOGO KLASTER
        ========================================== */

        .league-icon {

            width: 40px;

            height: 40px;

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


        .cluster-wrapper {

            width: 42px;

            height: 42px;

        }



        /* =========================================
           ICON BINTANG / TROPHY
        ========================================== */

        .col-points img {

            flex-shrink: 0;

        }



        /* =========================================
           MOBILE
        ========================================== */

        @media (max-width: 768px) {


            .table-header .col-points,
            .table-header .col-league {

                font-size: 13px;

            }


            .rank-item .col-user {

                font-size: 14px !important;

                gap: 8px !important;

                padding-left: 8px !important;

            }


            .rank-item .col-points {

                font-size: 14px !important;

            }


            .rank-item .col-league {

                font-size: 12px !important;

                gap: 5px !important;

            }


            .leaderboard-avatar {

                width: 32px;

                height: 32px;

            }


            .league-icon {

                width: 32px;

                height: 32px;

            }


        }


    </style>


</body>

</html>