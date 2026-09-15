<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UmaThink - Belajar &amp; Bermain</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/game_dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/halamanplay.css') }}">

    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
</head>
<body>

    @include('layouts.app')

    <div class="app-container">
        <aside class="sidebar">
            <div class="logo-top">
                <img src="{{ asset('assets/img/umathink_logo_text.png') }}" alt="UmaThink" class="sidebar-logo">
            </div>
            <nav class="side-nav">
                <a href="{{ route('game.dashboard') }}" class="nav-item active">
                    <i class="fa-solid fa-book-open"></i>
                </a>
                <a href="{{ route('game.leaderboard') }}" class="nav-item">
                    <i class="fa-solid fa-chart-simple"></i>
                </a>
                <a href="{{ route('customer.profile') }}" class="nav-item">
                    <i class="fa-solid fa-user"></i>
                </a>
            </nav>
        </aside>

        <main class="main-content">

            <div class="game-container">
                @if(session('success'))
                    <div class="alert-feedback alert-success" id="alertFeedback">
                        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert-feedback alert-error" id="alertFeedbackError">
                        <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
                    </div>
                @endif

                @if(session('pembahasan'))
                    <div class="alert-feedback info-box-custom" style="display: flex; flex-direction: column; align-items: flex-start; text-align: left; padding: 15px; margin-top: 10px; margin-bottom: 20px;">
                        <div style="font-weight: 800; margin-bottom: 8px; font-size: 14px;"><i class="fa-solid fa-lightbulb" style="color: #F8CB2E; margin-right: 5px;"></i> Pembahasan Soal Sebelumnya:</div>
                        <div style="font-size: 14px; line-height: 1.5; font-weight: 600;">{{ session('pembahasan') }}</div>
                    </div>
                @endif

                <header class="game-header">
                    <div class="level-badge">
                        <i class="fa-solid fa-layer-group"></i> Level {{ $levelNumber }}
                    </div>
                    <h1 class="game-title">{{ $gameCategory->nama_game }}</h1>

                    @if($currentQuestion)
                        {{-- Indikator Soal --}}
                        <div class="question-progress">
                            <span class="progress-text">Soal {{ $questionNumber }}</span>
                        </div>
                    @endif
                </header>

                @if(!$currentQuestion)
                    <div class="empty-state-box info-box-custom" style="text-align: center; padding: 45px 20px; border-radius: 20px; margin-top: 20px;">
                        <div style="font-size: 56px; color: #F8CB2E; margin-bottom: 15px;">
                            <i class="fa-solid fa-box-open"></i>
                        </div>
                        <h2 style="font-size: 22px; font-weight: 800; margin-bottom: 10px;">Soal Belum Tersedia</h2>
                        <p style="font-size: 15px; opacity: 0.85; max-width: 450px; margin: 0 auto 25px auto; line-height: 1.5;">
                            Game atau level ini belum memiliki soal. Admin belum menambahkan soal untuk level ini.
                        </p>
                        <a href="{{ route('game.levels', $gameCategory->slug) }}" class="btn-nav btn-back-level" style="display: inline-flex; align-items: center; gap: 8px; background-color: #F8CB2E; color: #1a1a2e; font-weight: 700; padding: 12px 24px; border-radius: 12px; text-decoration: none; box-shadow: 0 4px 12px rgba(248, 203, 46, 0.3);">
                            <i class="fa-solid fa-arrow-left"></i> Kembali ke Level
                        </a>
                    </div>
                @else
                    <div id="timerDisplay" class="info-box-custom" style="font-weight: 800; padding: 10px 20px; border-radius: 20px; text-align: center; font-size: 20px; margin-bottom: 20px; width: fit-content; margin-left: auto; margin-right: auto; display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                        <i class="fa-solid fa-stopwatch" style="color: #E74C3C; font-size: 24px;"></i> 
                        <div>Waktu: <span id="timeRemaining" style="color: #E74C3C;">{{ $currentQuestion->waktu ?? 60 }}</span> Detik</div>
                    </div>

                    <main class="game-main">
                        
                        <div class="question-box">
                            "{{ $currentQuestion->teks_soal }}"
                        </div>

                        <form action="{{ route('game.check') }}" method="POST" id="quizForm">

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

                            <div class="answers-grid">
                                @foreach($answers as $index => $answer)
                                    @if(!empty(trim($answer->teks_jawaban)))
                                        <label class="answer-card option-{{ $index + 1 }}">
                                            <input type="radio" name="selected_answer" value="{{ $answer->id }}" required>
                                            <span class="answer-text">{{ $answer->teks_jawaban }}</span>
                                        </label>
                                    @endif
                                @endforeach
                            </div>

                            <div class="action-container">
                                <button type="submit" class="btn-check" id="btnCheck">Periksa</button>
                            </div>
                        </form>
                    </main>

                    <div class="navigation-footer">
                        <a href="{{ route('game.levels', $gameCategory->slug) }}" class="btn-nav btn-back-level">
                            <i class="fa-solid fa-arrow-left"></i> Kembali ke Level
                        </a>
                    </div>
                @endif
            </div>
        </main>
    </div>

    <script>
        // Mencegah user menggunakan tombol Back di browser
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };

        // Auto-hide alert setelah 3 detik
        ['alertFeedback', 'alertFeedbackError'].forEach(id => {
            const alert = document.getElementById(id);
            if (alert) {
                setTimeout(() => {
                    alert.style.animation = 'fadeOutAlert 0.4s ease-out forwards';
                    setTimeout(() => alert.remove(), 400);
                }, 3000);
            }
        });

        @if($currentQuestion)
            // Aktifkan tombol Periksa setelah jawaban dipilih
            const radioButtons = document.querySelectorAll('input[name="selected_answer"]');
            const btnCheck = document.getElementById('btnCheck');

            radioButtons.forEach(radio => {
                radio.addEventListener('change', () => {
                    if (document.querySelector('input[name="selected_answer"]:checked')) {
                        btnCheck.classList.add('active');
                    }
                });
            });

            // Countdown Timer Logic
            let timeLeft = {{ $currentQuestion->waktu ?? 60 }};
            const timerElement = document.getElementById('timeRemaining');
            const quizForm = document.getElementById('quizForm');
            
            const countdown = setInterval(() => {
                timeLeft--;
                timerElement.textContent = timeLeft;
                
                // Warning color when < 10 seconds
                if(timeLeft <= 10) {
                    timerElement.style.animation = 'blink 1s infinite';
                }
                
                if (timeLeft <= 0) {
                    clearInterval(countdown);
                    timerElement.textContent = 0;
                    // Remove required attribute so the form can be submitted empty if time runs out
                    document.querySelectorAll('input[name="selected_answer"]').forEach(r => r.removeAttribute('required'));
                    
                    // Tampilkan loading state
                    const btnCheck = document.getElementById('btnCheck');
                    btnCheck.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Waktu Habis...';
                    btnCheck.classList.add('active');
                    btnCheck.style.backgroundColor = '#E74C3C';
                    
                    quizForm.submit();
                }
            }, 1000);
            
            // Add blink animation dynamically
            const style = document.createElement('style');
            style.innerHTML = `
                @keyframes blink {
                    0% { opacity: 1; }
                    50% { opacity: 0.3; }
                    100% { opacity: 1; }
                }
            `;
            document.head.appendChild(style);
        @endif
    </script>
</body>
</html>