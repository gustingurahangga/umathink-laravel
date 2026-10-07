<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>UmaThink - Pricelist</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    >

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pricelist.css') }}">

    <link
        rel="icon"
        type="image/png"
        href="{{ asset('assets/img/logo.png') }}"
    >
</head>

<body>

    @include('layouts.app')

    <main class="pricelist-page">

        <section class="pricing-hero">

            <h1 class="pricing-title">Pilih Paket Belajarmu</h1>

            <p class="pricing-subtitle">
                Pilih paket yang sesuai dengan kebutuhanmu dan
                tingkatkan persiapan menghadapi tes kedinasan
                dan tes lainnya bersama UmaThink.
            </p>
        </section>

        <section class="pricing-grid" aria-label="Daftar paket UmaThink">

            {{-- STARTER --}}
            <article class="pricing-card">
                <div class="pricing-card-head">
                    <span class="pricing-badge">STARTER</span>

                    <h2 class="pricing-name">Starter</h2>

                    <p class="pricing-description">
                        Untuk memulai perjalanan belajar dan mengenal
                        sistem latihan UmaThink.
                    </p>

                    <div class="pricing-price">
                        <span class="currency">Rp</span>
                        <span class="amount">15.000</span>
                        <span class="period">/bulan</span>
                    </div>

                    <a href="#" class="pricing-button">
                        Pilih Paket
                    </a>
                </div>

                <div class="pricing-features">
                    <h3>Yang Didapat</h3>

                    <ul>
                        <li>
                            <i class="fa-solid fa-check"></i>
                            Akses latihan soal dasar
                        </li>
                        <li>
                            <i class="fa-solid fa-check"></i>
                            Beberapa game dan level
                        </li>
                        <li>
                            <i class="fa-solid fa-check"></i>
                            Sistem bintang &amp; klaster
                        </li>
                        <li>
                            <i class="fa-solid fa-check"></i>
                            Leaderboard
                        </li>
                        <li>
                            <i class="fa-solid fa-check"></i>
                            Profil pengguna
                        </li>
                    </ul>
                </div>
            </article>


            {{-- PRO --}}
            <article class="pricing-card featured">
                <div class="popular-label">
                    PALING POPULER
                </div>

                <div class="pricing-card-head">
                    <span class="pricing-badge">PRO</span>

                    <h2 class="pricing-name">Pro</h2>

                    <p class="pricing-description">
                        Untuk latihan yang lebih lengkap dan persiapan
                        tes yang lebih serius.
                    </p>

                    <div class="pricing-price">
                        <span class="currency">Rp</span>
                        <span class="amount">35.000</span>
                        <span class="period">/bulan</span>
                    </div>

                    <a href="#" class="pricing-button">
                        Pilih Paket
                    </a>
                </div>

                <div class="pricing-features">
                    <h3>Yang Didapat</h3>

                    <ul>
                        <li>
                            <i class="fa-solid fa-check"></i>
                            Semua fitur Starter
                        </li>
                        <li>
                            <i class="fa-solid fa-check"></i>
                            Akses seluruh game &amp; level
                        </li>
                        <li>
                            <i class="fa-solid fa-check"></i>
                            Bank soal lebih lengkap
                        </li>
                        <li>
                            <i class="fa-solid fa-check"></i>
                            Latihan CPNS &amp; kedinasan
                        </li>
                        <li>
                            <i class="fa-solid fa-check"></i>
                            Pembahasan soal
                        </li>
                        <li>
                            <i class="fa-solid fa-check"></i>
                            Tryout
                        </li>
                        <li>
                            <i class="fa-solid fa-check"></i>
                            Statistik perkembangan belajar
                        </li>
                    </ul>
                </div>
            </article>


            {{-- PREMIUM --}}
            <article class="pricing-card">
                <div class="pricing-card-head">
                    <span class="pricing-badge">PREMIUM</span>

                    <h2 class="pricing-name">Premium</h2>

                    <p class="pricing-description">
                        Untuk persiapan intensif dengan pengalaman belajar
                        dan fitur yang lebih lengkap.
                    </p>

                    <div class="pricing-price">
                        <span class="currency">Rp</span>
                        <span class="amount">59.000</span>
                        <span class="period">/bulan</span>
                    </div>

                    <a href="#" class="pricing-button">
                        Pilih Paket
                    </a>
                </div>

                <div class="pricing-features">
                    <h3>Yang Didapat</h3>

                    <ul>
                        <li>
                            <i class="fa-solid fa-check"></i>
                            Semua fitur Pro
                        </li>
                        <li>
                            <i class="fa-solid fa-check"></i>
                            Tryout premium
                        </li>
                        <li>
                            <i class="fa-solid fa-check"></i>
                            Soal tingkat lanjut
                        </li>
                        <li>
                            <i class="fa-solid fa-check"></i>
                            Analisis hasil belajar
                        </li>
                        <li>
                            <i class="fa-solid fa-check"></i>
                            Rekomendasi latihan personal
                        </li>
                        <li>
                            <i class="fa-solid fa-check"></i>
                            Fitur AI untuk belajar
                        </li>
                        <li>
                            <i class="fa-solid fa-check"></i>
                            Akses fitur terbaru
                        </li>
                    </ul>
                </div>
            </article>

        </section>

    </main>

</body>
</html>
