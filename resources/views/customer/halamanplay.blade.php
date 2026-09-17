<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>UmaThink - Belajar &amp; Bermain</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    >

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/game_dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/halamanplay.css') }}">

    <link
        rel="icon"
        type="image/png"
        href="{{ asset('assets/img/logo.png') }}"
    >

    <style>
        /* =========================================================
           MATCHING
        ========================================================== */

        .matching-container {
            width: 100%;
            max-width: 760px;
            margin: 10px auto 0;
        }

        .matching-instruction {
            text-align: center;
            margin-bottom: 25px;
            font-size: 17px;
            font-weight: 700;
            color: #333;
            line-height: 1.5;
        }

        .matching-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            width: 100%;
        }

        .matching-column {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .matching-item {
            width: 100%;
            min-height: 56px;
            border: none;
            border-radius: 13px;
            padding: 12px 16px;
            font-family: inherit;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        .matching-left-item {
            background: #4AA3CF;
            color: #fff;
        }

        .matching-right-item {
            background: #FF9900;
            color: #fff;
        }

        .matching-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.12);
        }

        .matching-item.selected {
            outline: 3px solid #FFD700;
            transform: scale(1.02);
        }

        .matching-item.matched {
            opacity: 0.65;
            cursor: default;
        }

        .matching-item.matched:hover {
            transform: none;
        }

        .matching-status {
            text-align: center;
            margin-top: 18px;
            font-size: 13px;
            font-weight: 700;
            color: #777;
        }

        .matching-warning {
            text-align: center;
            margin-top: 10px;
            font-size: 13px;
            font-weight: 700;
            color: #E74C3C;
            display: none;
        }


        /* =========================================================
           PERNYATAAN
        ========================================================== */

        .statement-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto 10px;
        }

        .statement-instruction {
            text-align: center;
            margin-bottom: 28px;
            font-size: 17px;
            font-weight: 700;
            color: #333;
            line-height: 1.5;
        }

        .statement-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;

            /* Jarak antara YA/SALAH dengan tombol Periksa */
            margin-bottom: 35px;
        }

        .statement-card {
            min-height: 220px;
            border-radius: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .statement-card:nth-child(1) {
            background: #4AA3CF;
        }

        .statement-card:nth-child(2) {
            background: #14CC91;
        }

        .statement-card input {
            display: none;
        }

        .statement-text {
            color: #fff;
            font-size: 38px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .statement-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12);
        }

        .statement-card:has(input:checked) {
            outline: 4px solid #FFD700;
            transform: scale(1.02);
        }


        /* =========================================================
           BUTTON DISABLED
        ========================================================== */

        .btn-check:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
        }


        /* =========================================================
           RESPONSIVE
        ========================================================== */

        @media (max-width: 700px) {

            .matching-columns {
                gap: 10px;
            }

            .matching-item {
                min-height: 50px;
                padding: 10px 12px;
                font-size: 13px;
            }

        }

        @media (max-width: 600px) {

            .statement-options {
                grid-template-columns: 1fr;
                margin-bottom: 30px;
            }

            .statement-card {
                min-height: 150px;
            }

            .statement-text {
                font-size: 30px;
            }

        }

        @media (max-width: 500px) {

            .matching-columns {
                grid-template-columns: 1fr;
            }

        }
    </style>

</head>

<body>

    @include('layouts.app')

    <div class="app-container">

        {{-- =====================================================
             SIDEBAR
        ====================================================== --}}

        <aside class="sidebar">

            <div class="logo-top">

                <img
                    src="{{ asset('assets/img/umathink_logo_text.png') }}"
                    alt="UmaThink"
                    class="sidebar-logo"
                >

            </div>

            <nav class="side-nav">

                <a
                    href="{{ route('game.dashboard') }}"
                    class="nav-item active"
                >
                    <i class="fa-solid fa-book-open"></i>
                </a>

                <a
                    href="{{ route('game.leaderboard') }}"
                    class="nav-item"
                >
                    <i class="fa-solid fa-chart-simple"></i>
                </a>

                <a
                    href="{{ route('customer.profile') }}"
                    class="nav-item"
                >
                    <i class="fa-solid fa-user"></i>
                </a>

            </nav>

        </aside>


        {{-- =====================================================
             MAIN
        ====================================================== --}}

        <main class="main-content">

            <div class="game-container">

                {{-- SUCCESS --}}
                @if(session('success'))

                    <div
                        class="alert-feedback alert-success"
                        id="alertFeedback"
                    >
                        <i class="fa-solid fa-circle-check"></i>
                        {{ session('success') }}
                    </div>

                @endif


                {{-- ERROR --}}
                @if(session('error'))

                    <div
                        class="alert-feedback alert-error"
                        id="alertFeedbackError"
                    >
                        <i class="fa-solid fa-circle-xmark"></i>
                        {{ session('error') }}
                    </div>

                @endif


                {{-- PEMBAHASAN --}}
                @if(session('pembahasan'))

                    <div
                        class="alert-feedback info-box-custom"
                        style="
                            display:flex;
                            flex-direction:column;
                            align-items:flex-start;
                            text-align:left;
                            padding:15px;
                            margin-top:10px;
                            margin-bottom:20px;
                        "
                    >

                        <div
                            style="
                                font-weight:800;
                                margin-bottom:8px;
                                font-size:14px;
                            "
                        >

                            <i
                                class="fa-solid fa-lightbulb"
                                style="
                                    color:#F8CB2E;
                                    margin-right:5px;
                                "
                            ></i>

                            Pembahasan Soal Sebelumnya:

                        </div>

                        <div
                            style="
                                font-size:14px;
                                line-height:1.5;
                                font-weight:600;
                            "
                        >
                            {{ session('pembahasan') }}
                        </div>

                    </div>

                @endif


                {{-- =====================================================
                     HEADER
                ====================================================== --}}

                <header class="game-header">

                    <div class="level-badge">

                        <i class="fa-solid fa-layer-group"></i>

                        Level {{ $levelNumber }}

                    </div>

                    <h1 class="game-title">
                        {{ $gameCategory->nama_game }}
                    </h1>

                    @if($currentQuestion)

                        <div class="question-progress">

                            <span class="progress-text">
                                Soal {{ $questionNumber }}
                            </span>

                        </div>

                    @endif

                </header>


                {{-- =====================================================
                     EMPTY STATE
                ====================================================== --}}

                @if(!$currentQuestion)

                    <div
                        class="empty-state-box info-box-custom"
                        style="
                            text-align:center;
                            padding:45px 20px;
                            border-radius:20px;
                            margin-top:20px;
                        "
                    >

                        <div
                            style="
                                font-size:56px;
                                color:#F8CB2E;
                                margin-bottom:15px;
                            "
                        >
                            <i class="fa-solid fa-box-open"></i>
                        </div>

                        <h2
                            style="
                                font-size:22px;
                                font-weight:800;
                                margin-bottom:10px;
                            "
                        >
                            Soal Belum Tersedia
                        </h2>

                        <p
                            style="
                                font-size:15px;
                                opacity:0.85;
                                max-width:450px;
                                margin:0 auto 25px auto;
                                line-height:1.5;
                            "
                        >
                            Game atau level ini belum memiliki soal.
                            Admin belum menambahkan soal untuk level ini.
                        </p>

                        <a
                            href="{{ route('game.levels', $gameCategory->slug) }}"
                            class="btn-nav btn-back-level"
                            style="
                                display:inline-flex;
                                align-items:center;
                                gap:8px;
                                background-color:#F8CB2E;
                                color:#1a1a2e;
                                font-weight:700;
                                padding:12px 24px;
                                border-radius:12px;
                                text-decoration:none;
                                box-shadow:0 4px 12px rgba(248,203,46,0.3);
                            "
                        >
                            <i class="fa-solid fa-arrow-left"></i>
                            Kembali ke Level
                        </a>

                    </div>

                @else

                    {{-- =================================================
                         TIMER
                    ================================================== --}}

                    <div
                        id="timerDisplay"
                        class="info-box-custom"
                        style="
                            font-weight:800;
                            padding:10px 20px;
                            border-radius:20px;
                            text-align:center;
                            font-size:20px;
                            margin-bottom:20px;
                            width:fit-content;
                            margin-left:auto;
                            margin-right:auto;
                            display:flex;
                            align-items:center;
                            gap:10px;
                            box-shadow:0 4px 6px rgba(0,0,0,0.05);
                        "
                    >

                        <i
                            class="fa-solid fa-stopwatch"
                            style="
                                color:#E74C3C;
                                font-size:24px;
                            "
                        ></i>

                        <div>

                            Waktu:

                            <span
                                id="timeRemaining"
                                style="color:#E74C3C;"
                            >
                                {{ $currentQuestion->waktu ?? 60 }}
                            </span>

                            Detik

                        </div>

                    </div>


                    {{-- =================================================
                         GAME MAIN
                    ================================================== --}}

                    <main class="game-main">

                        {{-- SOAL --}}
                        <div class="question-box">

                            "{{ $currentQuestion->teks_soal }}"

                        </div>


                        {{-- FORM --}}
                        <form
                            action="{{ route('game.check') }}"
                            method="POST"
                            id="quizForm"
                        >

                            @csrf


                            <input
                                type="hidden"
                                name="question_id"
                                value="{{ $currentQuestion->id }}"
                            >

                            <input
                                type="hidden"
                                name="slug"
                                value="{{ $gameCategory->slug }}"
                            >

                            <input
                                type="hidden"
                                name="level_number"
                                value="{{ $levelNumber }}"
                            >

                            <input
                                type="hidden"
                                name="question_number"
                                value="{{ $questionNumber }}"
                            >

                            <input
                                type="hidden"
                                name="total_questions"
                                value="{{ $totalQuestions }}"
                            >


                            {{-- =================================================
                                 MATCHING
                            ================================================== --}}

                            @if($currentQuestion->tipe_soal === 'matching')

                                <div class="matching-container">

                                    <div class="matching-instruction">

                                        Cocokkan setiap kata di sebelah kiri
                                        dengan pasangan yang tepat di sebelah kanan!

                                    </div>


                                    <div class="matching-columns">

                                        {{-- KIRI --}}
                                        <div class="matching-column matching-left">

                                            @foreach($matchingPairs as $pair)

                                                @if(
                                                    isset($pair['kiri']) &&
                                                    trim((string) $pair['kiri']) !== ''
                                                )

                                                    <button
                                                        type="button"
                                                        class="matching-item matching-left-item"
                                                        data-left="{{ $pair['kiri'] }}"
                                                    >
                                                        {{ $pair['kiri'] }}
                                                    </button>

                                                @endif

                                            @endforeach

                                        </div>


                                        {{-- KANAN --}}
                                        <div class="matching-column matching-right">

                                            @foreach($matchingOptions as $option)

                                                @if(
                                                    trim((string) $option) !== ''
                                                )

                                                    <button
                                                        type="button"
                                                        class="matching-item matching-right-item"
                                                        data-right="{{ $option }}"
                                                    >
                                                        {{ $option }}
                                                    </button>

                                                @endif

                                            @endforeach

                                        </div>

                                    </div>


                                    <input
                                        type="hidden"
                                        name="matching_answers"
                                        id="matchingAnswers"
                                        value="{}"
                                    >


                                    <div
                                        class="matching-status"
                                        id="matchingStatus"
                                    >
                                        0 / {{ count($matchingPairs) }}
                                        pasangan telah dicocokkan
                                    </div>


                                    <div
                                        class="matching-warning"
                                        id="matchingWarning"
                                    >
                                        Silakan lengkapi semua pasangan
                                        terlebih dahulu.
                                    </div>

                                </div>


                            {{-- =================================================
                                 PERNYATAAN
                            ================================================== --}}

                            @elseif($currentQuestion->tipe_soal === 'pernyataan')

                                <div class="statement-container">

                                    <div class="statement-instruction">

                                        Apakah pasangan kata berikut
                                        memiliki arti yang sesuai?

                                    </div>


                                    <div class="statement-options">

                                        @foreach($answers as $answer)

                                            @if(
                                                trim((string) $answer->teks_jawaban) !== ''
                                            )

                                                <label class="statement-card">

                                                    <input
                                                        type="radio"
                                                        name="selected_answer"
                                                        value="{{ $answer->id }}"
                                                        required
                                                    >

                                                    <span class="statement-text">

                                                        {{ strtoupper($answer->teks_jawaban) }}

                                                    </span>

                                                </label>

                                            @endif

                                        @endforeach

                                    </div>

                                </div>


                            {{-- =================================================
                                 PILIHAN GANDA
                            ================================================== --}}

                            @else

                                <div class="answers-grid">

                                    @foreach($answers as $index => $answer)

                                        @if(
                                            !empty(trim($answer->teks_jawaban))
                                        )

                                            <label
                                                class="answer-card option-{{ $index + 1 }}"
                                            >

                                                <input
                                                    type="radio"
                                                    name="selected_answer"
                                                    value="{{ $answer->id }}"
                                                    required
                                                >

                                                <span class="answer-text">

                                                    {{ $answer->teks_jawaban }}

                                                </span>

                                            </label>

                                        @endif

                                    @endforeach

                                </div>

                            @endif


                            {{-- =================================================
                                 BUTTON PERIKSA
                            ================================================== --}}

                            <div class="action-container">

                                <button
                                    type="submit"
                                    class="btn-check"
                                    id="btnCheck"
                                    @if(
                                        $currentQuestion->tipe_soal === 'matching'
                                    )
                                        disabled
                                    @endif
                                >
                                    Periksa
                                </button>

                            </div>

                        </form>

                    </main>


                    {{-- =================================================
                         NAVIGATION
                    ================================================== --}}

                    <div class="navigation-footer">

                        <a
                            href="{{ route('game.levels', $gameCategory->slug) }}"
                            class="btn-nav btn-back-level"
                        >

                            <i class="fa-solid fa-arrow-left"></i>

                            Kembali ke Level

                        </a>

                    </div>

                @endif

            </div>

        </main>

    </div>


    {{-- =========================================================
         JAVASCRIPT
    ========================================================== --}}

    <script>

        /* =========================================================
           PREVENT BACK BUTTON
        ========================================================== */

        history.pushState(null, null, location.href);

        window.onpopstate = function () {
            history.go(1);
        };


        /* =========================================================
           AUTO HIDE ALERT
        ========================================================== */

        ['alertFeedback', 'alertFeedbackError'].forEach(id => {

            const alert = document.getElementById(id);

            if (alert) {

                setTimeout(() => {

                    alert.style.animation =
                        'fadeOutAlert 0.4s ease-out forwards';

                    setTimeout(() => {

                        alert.remove();

                    }, 400);

                }, 3000);

            }

        });


        @if($currentQuestion)

            const quizForm =
                document.getElementById('quizForm');

            const btnCheck =
                document.getElementById('btnCheck');

            const timerElement =
                document.getElementById('timeRemaining');


            /* =====================================================
               PILIHAN GANDA + PERNYATAAN
            ====================================================== */

            @if($currentQuestion->tipe_soal !== 'matching')

                const radioButtons =
                    document.querySelectorAll(
                        'input[name="selected_answer"]'
                    );

                radioButtons.forEach(radio => {

                    radio.addEventListener('change', () => {

                        const selected =
                            document.querySelector(
                                'input[name="selected_answer"]:checked'
                            );

                        if (selected) {

                            btnCheck.disabled = false;

                            btnCheck.classList.add(
                                'active'
                            );

                        }

                    });

                });

            @endif


            /* =====================================================
               MATCHING
            ====================================================== */

            @if($currentQuestion->tipe_soal === 'matching')

                const matchingAnswers = {};

                let selectedLeft = null;
                let selectedRight = null;


                const leftItems =
                    document.querySelectorAll(
                        '.matching-left-item'
                    );

                const rightItems =
                    document.querySelectorAll(
                        '.matching-right-item'
                    );

                const matchingInput =
                    document.getElementById(
                        'matchingAnswers'
                    );

                const matchingStatus =
                    document.getElementById(
                        'matchingStatus'
                    );

                const matchingWarning =
                    document.getElementById(
                        'matchingWarning'
                    );

                const totalPairs =
                    {{ count($matchingPairs) }};


                function updateMatchingInput() {

                    matchingInput.value =
                        JSON.stringify(matchingAnswers);


                    const completedPairs =
                        Object.keys(
                            matchingAnswers
                        ).length;


                    matchingStatus.textContent =
                        `${completedPairs} / ${totalPairs} pasangan telah dicocokkan`;


                    if (
                        completedPairs === totalPairs
                    ) {

                        btnCheck.disabled = false;

                        btnCheck.classList.add(
                            'active'
                        );

                        matchingWarning.style.display =
                            'none';

                    } else {

                        btnCheck.disabled = true;

                        btnCheck.classList.remove(
                            'active'
                        );

                    }

                }


                function clearSelection() {

                    leftItems.forEach(item => {

                        item.classList.remove(
                            'selected'
                        );

                    });


                    rightItems.forEach(item => {

                        item.classList.remove(
                            'selected'
                        );

                    });

                }


                function createMatch() {

                    if (
                        !selectedLeft ||
                        !selectedRight
                    ) {
                        return;
                    }


                    matchingAnswers[selectedLeft] =
                        selectedRight;


                    const leftElement =
                        document.querySelector(
                            `.matching-left-item[data-left="${CSS.escape(selectedLeft)}"]`
                        );


                    const rightElement =
                        document.querySelector(
                            `.matching-right-item[data-right="${CSS.escape(selectedRight)}"]`
                        );


                    if (leftElement) {

                        leftElement.classList.add(
                            'matched'
                        );

                    }


                    if (rightElement) {

                        rightElement.classList.add(
                            'matched'
                        );

                    }


                    selectedLeft = null;

                    selectedRight = null;


                    clearSelection();

                    updateMatchingInput();

                }


                leftItems.forEach(item => {

                    item.addEventListener(
                        'click',
                        function () {

                            if (
                                this.classList.contains(
                                    'matched'
                                )
                            ) {
                                return;
                            }


                            leftItems.forEach(
                                left => {

                                    if (
                                        !left.classList.contains(
                                            'matched'
                                        )
                                    ) {

                                        left.classList.remove(
                                            'selected'
                                        );

                                    }

                                }
                            );


                            this.classList.add(
                                'selected'
                            );


                            selectedLeft =
                                this.dataset.left;


                            createMatch();

                        }
                    );

                });


                rightItems.forEach(item => {

                    item.addEventListener(
                        'click',
                        function () {

                            if (
                                this.classList.contains(
                                    'matched'
                                )
                            ) {
                                return;
                            }


                            rightItems.forEach(
                                right => {

                                    if (
                                        !right.classList.contains(
                                            'matched'
                                        )
                                    ) {

                                        right.classList.remove(
                                            'selected'
                                        );

                                    }

                                }
                            );


                            this.classList.add(
                                'selected'
                            );


                            selectedRight =
                                this.dataset.right;


                            createMatch();

                        }
                    );

                });


                btnCheck.addEventListener(
                    'click',
                    function (event) {

                        const completedPairs =
                            Object.keys(
                                matchingAnswers
                            ).length;


                        if (
                            completedPairs < totalPairs
                        ) {

                            event.preventDefault();

                            matchingWarning.style.display =
                                'block';

                        }

                    }
                );


                updateMatchingInput();

            @endif


            /* =====================================================
               TIMER
            ====================================================== */

            let timeLeft =
                {{ $currentQuestion->waktu ?? 60 }};


            const countdown =
                setInterval(() => {

                    timeLeft--;


                    if (timeLeft < 0) {

                        timeLeft = 0;

                    }


                    timerElement.textContent =
                        timeLeft;


                    if (timeLeft <= 10) {

                        timerElement.style.animation =
                            'blink 1s infinite';

                    }


                    if (timeLeft <= 0) {

                        clearInterval(countdown);

                        timerElement.textContent =
                            0;


                        /* ------------------------------
                           Pilihan Ganda + Pernyataan
                        ------------------------------ */

                        document
                            .querySelectorAll(
                                'input[name="selected_answer"]'
                            )
                            .forEach(input => {

                                input.removeAttribute(
                                    'required'
                                );

                            });


                        /* ------------------------------
                           Matching
                        ------------------------------ */

                        @if(
                            $currentQuestion->tipe_soal === 'matching'
                        )

                            matchingInput.value =
                                JSON.stringify(
                                    matchingAnswers
                                );

                        @endif


                        btnCheck.disabled =
                            false;


                        btnCheck.innerHTML =
                            '<i class="fa-solid fa-spinner fa-spin"></i> Waktu Habis...';


                        btnCheck.classList.add(
                            'active'
                        );


                        btnCheck.style.backgroundColor =
                            '#E74C3C';


                        quizForm.submit();

                    }

                }, 1000);


            /* =====================================================
               BLINK TIMER
            ====================================================== */

            const style =
                document.createElement('style');


            style.innerHTML = `

                @keyframes blink {

                    0% {
                        opacity: 1;
                    }

                    50% {
                        opacity: 0.3;
                    }

                    100% {
                        opacity: 1;
                    }

                }

            `;


            document.head.appendChild(style);

        @endif

    </script>

</body>
</html>