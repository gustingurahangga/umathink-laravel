<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\GameCategory;
use App\Models\Question;
use App\Models\UserLevelScore;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GamePlayController extends Controller
{
    /**
     * Jumlah soal wajib dalam satu level.
     */
    private const QUESTIONS_PER_LEVEL = 10;

    /**
     * Minimal poin untuk mendapatkan bintang 1
     * dan membuka level berikutnya.
     */
    private const MINIMUM_PASSING_POINT = 18;

    /**
     * Menentukan poin berdasarkan jumlah jawaban benar.
     *
     * 0-5  = 0 poin
     * 6    = 18 poin
     * 7    = 21 poin
     * 8    = 24 poin
     * 9    = 27 poin
     * 10   = 30 poin
     */
    private function calculatePoint(int $correctAnswers): int
    {
        if ($correctAnswers <= 5) {
            return 0;
        }

        return $correctAnswers * 3;
    }

    /**
     * Menentukan bintang berdasarkan jumlah jawaban benar.
     *
     * 0-5  = 0 bintang
     * 6-7  = 1 bintang
     * 8-9  = 2 bintang
     * 10   = 3 bintang
     */
    private function calculateStars(int $correctAnswers): int
    {
        if ($correctAnswers <= 5) {
            return 0;
        }

        if ($correctAnswers <= 7) {
            return 1;
        }

        if ($correctAnswers <= 9) {
            return 2;
        }

        return 3;
    }

    /**
     * Menghitung ulang total poin user
     * berdasarkan nilai terbaik setiap level.
     *
     * Jadi tidak terjadi:
     * 18 + 21 + 27
     *
     * untuk level yang sama.
     */
    private function recalculateTotalPoints($user): void
    {
        $totalPoin = UserLevelScore::where('user_id', $user->id)
            ->sum('poin');

        $user->total_poin = $totalPoin;

        /*
         * Simpan pencapaian poin tertinggi user.
         */
        if ($totalPoin > ($user->highest_poin ?? 0)) {
            $user->highest_poin = $totalPoin;
            $user->highest_liga = $user->liga;
        }

        /*
         * Ranking terbaik.
         */
        $currentRank = $user->peringkat;

        if (is_numeric($currentRank)) {
            if (
                is_null($user->highest_peringkat) ||
                $currentRank < $user->highest_peringkat
            ) {
                $user->highest_peringkat = $currentRank;
            }
        }

        $user->save();
    }

    /**
     * Mengecek apakah level boleh dimainkan.
     */
    private function canPlayLevel($user, GameCategory $gameCategory, int $levelNumber): bool
    {
        if ($levelNumber < 1) {
            return false;
        }

        if ($levelNumber > $gameCategory->jumlah_level) {
            return false;
        }

        /*
         * Level 1 selalu terbuka.
         */
        if ($levelNumber === 1) {
            return true;
        }

        $progress = UserProgress::where('user_id', $user->id)
            ->where('game_category_id', $gameCategory->id)
            ->first();

        $unlockedLevel = $progress?->unlocked_level ?? 1;

        return $levelNumber <= $unlockedLevel;
    }

    /**
     * Mengecek apakah semua game selain Tryout
     * sudah diselesaikan.
     */
    private function allOtherGamesFinished($user): bool
    {
        $games = GameCategory::all();

        foreach ($games as $game) {
            $isTryout =
                strtolower($game->nama_game) === 'tryout' ||
                strtolower($game->slug) === 'tryout';

            if ($isTryout) {
                continue;
            }

            $progress = UserProgress::where('user_id', $user->id)
                ->where('game_category_id', $game->id)
                ->first();

            $unlockedLevel = $progress?->unlocked_level ?? 1;

            /*
             * Jika unlocked_level = jumlah_level + 1,
             * berarti seluruh level sudah selesai.
             */
            if ($unlockedLevel <= $game->jumlah_level) {
                return false;
            }
        }

        return true;
    }

    /**
     * Memulai / menampilkan soal.
     */
    public function play($slug, $level, $questionNumber = 1)
    {
        $gameCategory = GameCategory::where('slug', $slug)->firstOrFail();

        if ($gameCategory->status === 'maintenance') {
            return view('customer.maintenance', compact('gameCategory'));
        }

        $user = auth()->user();

        $levelNumber = (int) $level;
        $questionNumber = (int) $questionNumber;

        /*
         * ================================
         * CEK TRYOUT
         * ================================
         */
        $isTryout =
            strtolower($gameCategory->nama_game) === 'tryout' ||
            strtolower($gameCategory->slug) === 'tryout';

        if ($isTryout && !$this->allOtherGamesFinished($user)) {
            return redirect()
                ->route('game.dashboard')
                ->with(
                    'error',
                    'Selesaikan semua game lain terlebih dahulu untuk membuka Tryout!'
                );
        }

        /*
         * ================================
         * CEK LEVEL
         * ================================
         */
        if (!$this->canPlayLevel($user, $gameCategory, $levelNumber)) {
            return redirect()
                ->route('game.levels', $gameCategory->slug)
                ->with(
                    'error',
                    'Level tersebut masih terkunci. Dapatkan minimal 18 poin atau 1 bintang pada level sebelumnya.'
                );
        }

        /*
         * ================================
         * AMBIL SOAL
         * ================================
         */
        $questions = Question::where('game_category_id', $gameCategory->id)
            ->where('level', $levelNumber)
            ->orderBy('id', 'asc')
            ->get();

        /*
         * Client menetapkan:
         * 1 level = 10 soal.
         */
        if ($questions->count() !== self::QUESTIONS_PER_LEVEL) {
            return redirect()
                ->route('game.levels', $gameCategory->slug)
                ->with(
                    'error',
                    "Level {$levelNumber} belum siap. Level harus memiliki tepat 10 soal."
                );
        }

        if ($questionNumber < 1 || $questionNumber > self::QUESTIONS_PER_LEVEL) {
            $questionNumber = 1;
        }

        /*
         * ================================
         * SESSION ATTEMPT
         * ================================
         *
         * Setiap pengerjaan ulang dimulai
         * dari soal pertama.
         */
        $attemptKey = "quiz_attempt_{$slug}_level_{$levelNumber}";
        $currentQuestionKey = "current_question_{$slug}_level_{$levelNumber}";
        $answersKey = "quiz_answers_{$slug}_level_{$levelNumber}";

        /*
         * Jika user baru masuk level,
         * buat percobaan baru.
         */
        if (
            $questionNumber === 1 &&
            !session()->has($currentQuestionKey)
        ) {
            session([
                $attemptKey => true,
                $currentQuestionKey => 1,
                $answersKey => [],
            ]);
        }

        /*
         * Jika user mencoba membuka soal
         * sebelum nomor yang seharusnya.
         */
        $expectedQuestion = (int) session($currentQuestionKey, 1);

        if ($questionNumber < $expectedQuestion) {
            return redirect()
                ->route('game.play', [
                    'slug' => $slug,
                    'level' => $levelNumber,
                    'question' => $expectedQuestion,
                ])
                ->with(
                    'error',
                    'Anda tidak dapat kembali ke soal sebelumnya!'
                );
        }

        /*
         * Jangan izinkan lompat soal.
         */
        if ($questionNumber > $expectedQuestion) {
            return redirect()
                ->route('game.play', [
                    'slug' => $slug,
                    'level' => $levelNumber,
                    'question' => $expectedQuestion,
                ]);
        }

        $currentQuestion = $questions[$questionNumber - 1];

        $answers = $currentQuestion->answers()
            ->orderBy('id', 'asc')
            ->get();

        // Data khusus soal matching.
        $matchingPairs = [];
        $matchingOptions = [];

        if ($currentQuestion->tipe_soal === 'matching') {
            $matchingPairs = $currentQuestion->data_matching ?? [];

            $matchingOptions = collect($matchingPairs)
                ->pluck('kanan')
                ->shuffle()
                ->values()
                ->toArray();
        }

        $totalQuestions = self::QUESTIONS_PER_LEVEL;

        return view(
            'customer.halamanplay',
            compact(
                'gameCategory',
                'levelNumber',
                'currentQuestion',
                'answers',
                'matchingPairs',
                'matchingOptions',
                'questionNumber',
                'totalQuestions'
            )
        );
    }

    /**
     * Memeriksa jawaban user.
     *
     * Poin TIDAK diberikan per soal.
     * Poin dihitung setelah 10 soal selesai.
     */
    public function check(Request $request)
    {
        $request->validate([
            'question_id' => 'required|integer',
            'slug' => 'required|string',
            'level_number' => 'required|integer|min:1',
            'question_number' => 'required|integer|min:1|max:10',
            'total_questions' => 'required|integer',
            'selected_answer' => 'nullable|integer',
            'matching_answers' => 'nullable|string',
        ]);

        $user = auth()->user();

        $slug = $request->input('slug');
        $levelNumber = (int) $request->input('level_number');
        $questionNumber = (int) $request->input('question_number');
        $selectedAnswerId = (int) ($request->input('selected_answer') ?? 0);

        $gameCategory = GameCategory::where('slug', $slug)->firstOrFail();

        if ($gameCategory->status === 'maintenance') {
            return view('customer.maintenance', compact('gameCategory'));
        }

        /*
         * Cek apakah level masih boleh dimainkan.
         */
        if (!$this->canPlayLevel($user, $gameCategory, $levelNumber)) {
            return redirect()
                ->route('game.levels', $gameCategory->slug)
                ->with('error', 'Level tersebut masih terkunci.');
        }

        /*
         * Pastikan level benar-benar mempunyai 10 soal.
         */
        $questions = Question::where('game_category_id', $gameCategory->id)
            ->where('level', $levelNumber)
            ->orderBy('id', 'asc')
            ->get();

        if ($questions->count() !== self::QUESTIONS_PER_LEVEL) {
            return redirect()
                ->route('game.levels', $gameCategory->slug)
                ->with(
                    'error',
                    "Level {$levelNumber} harus memiliki tepat 10 soal."
                );
        }

        $question = Question::findOrFail(
            (int) $request->input('question_id')
        );

        /*
         * Pastikan soal memang milik game + level tersebut.
         */
        if (
            $question->game_category_id != $gameCategory->id ||
            $question->level != $levelNumber
        ) {
            abort(403, 'Soal tidak valid.');
        }

        /*
         * Pastikan nomor soal sesuai dengan ID soal.
         */
        $expectedQuestion = $questions[$questionNumber - 1];

        if ($expectedQuestion->id !== $question->id) {
            abort(403, 'Urutan soal tidak valid.');
        }

        /*
         * Pastikan user tidak mengirim ulang
         * soal yang sudah dijawab.
         */
        $currentQuestionKey = "current_question_{$slug}_level_{$levelNumber}";
        $answersKey = "quiz_answers_{$slug}_level_{$levelNumber}";

        $expectedQuestionNumber = (int) session(
            $currentQuestionKey,
            1
        );

        if ($questionNumber !== $expectedQuestionNumber) {
            return redirect()
                ->route('game.play', [
                    'slug' => $slug,
                    'level' => $levelNumber,
                    'question' => $expectedQuestionNumber,
                ])
                ->with(
                    'error',
                    'Soal tersebut sudah diproses.'
                );
        }

        /*
         * ================================
         * CEK JAWABAN
         * ================================
         */
        $isCorrect = false;

        if ($question->tipe_soal === 'matching') {
            /*
             * Matching dikirim sebagai JSON:
             * {
             *     "Terang": "Cahaya",
             *     "Cerdas": "Pandai"
             * }
             */
            $submittedRaw = (string) $request->input('matching_answers', '{}');
            $submitted = json_decode($submittedRaw, true);

            if (is_array($submitted)) {
                $correctPairs = [];

                foreach (($question->data_matching ?? []) as $pair) {
                    if (
                        is_array($pair) &&
                        isset($pair['kiri']) &&
                        isset($pair['kanan'])
                    ) {
                        $correctPairs[trim((string) $pair['kiri'])] =
                            trim((string) $pair['kanan']);
                    }
                }

                $submittedPairs = [];

                foreach ($submitted as $kiri => $kanan) {
                    if (
                        is_string($kiri) &&
                        is_string($kanan) &&
                        trim($kiri) !== '' &&
                        trim($kanan) !== ''
                    ) {
                        $submittedPairs[trim($kiri)] = trim($kanan);
                    }
                }

                ksort($correctPairs);
                ksort($submittedPairs);

                // Semua pasangan harus tepat agar soal bernilai benar.
                $isCorrect =
                    !empty($correctPairs) &&
                    $correctPairs === $submittedPairs;
            }

        } else {
            // Pilihan ganda dan pernyataan menggunakan tabel answers.
            if ($selectedAnswerId > 0) {
                $answer = Answer::where('id', $selectedAnswerId)
                    ->where('question_id', $question->id)
                    ->first();

                if ($answer) {
                    $isCorrect = (bool) $answer->is_correct;
                }
            }
        }

        /*
         * Simpan hasil jawaban dalam session.
         *
         * Contoh:
         * [
         *     1 => true,
         *     2 => false,
         *     3 => true
         * ]
         */
        $attemptAnswers = session($answersKey, []);

        $attemptAnswers[$questionNumber] = $isCorrect;

        session([
            $answersKey => $attemptAnswers,
            $currentQuestionKey => $questionNumber + 1,
        ]);

        /*
         * Pembahasan.
         */
        $pembahasan = $question->pembahasan;

        /*
         * ================================
         * JIKA MASIH ADA SOAL
         * ================================
         */
        if ($questionNumber < self::QUESTIONS_PER_LEVEL) {

            if ($isCorrect) {
                $message = 'Benar! 🎉 Lanjut ke soal berikutnya.';
                $messageType = 'success';
            } else {
                if ($selectedAnswerId === 0) {
                    $message = 'Waktu habis! Jawaban tidak dipilih. Lanjut ke soal berikutnya.';
                } else {
                    $message = 'Yah! Jawaban salah. Tetap semangat!';
                }

                $messageType = 'error';
            }

            return redirect()
                ->route('game.play', [
                    'slug' => $slug,
                    'level' => $levelNumber,
                    'question' => $questionNumber + 1,
                ])
                ->with($messageType, $message)
                ->with('pembahasan', $pembahasan);
        }

        /*
         * ================================
         * LEVEL SELESAI
         * ================================
         */

        /*
         * Pastikan semua 10 jawaban sudah tercatat.
         */
        $correctAnswers = collect($attemptAnswers)
            ->filter(fn ($value) => $value === true)
            ->count();

        /*
         * Hitung poin berdasarkan jumlah benar.
         */
        $poin = $this->calculatePoint($correctAnswers);

        /*
         * Hitung bintang.
         */
        $stars = $this->calculateStars($correctAnswers);

        /*
         * Ambil nilai terbaik sebelumnya.
         */
        $previousScore = UserLevelScore::where('user_id', $user->id)
            ->where('game_category_id', $gameCategory->id)
            ->where('level', $levelNumber)
            ->first();

        $previousPoin = $previousScore?->poin ?? 0;
        $previousStars = $previousScore?->stars ?? 0;
        $previousCorrect = $previousScore?->correct_answers ?? 0;

        /*
         * Default:
         * nilai percobaan sekarang belum tentu
         * menjadi nilai terbaik.
         */
        $isNewBestScore = false;

        /*
         * HANYA update jika hasil sekarang lebih tinggi.
         */
        if (
            !$previousScore ||
            $poin > $previousPoin ||
            (
                $poin === $previousPoin &&
                $correctAnswers > $previousCorrect
            )
        ) {
            UserLevelScore::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'game_category_id' => $gameCategory->id,
                    'level' => $levelNumber,
                ],
                [
                    'correct_answers' => $correctAnswers,
                    'poin' => $poin,
                    'stars' => $stars,
                ]
            );

            $isNewBestScore = true;
        }

        /*
         * Ambil score terbaik setelah update.
         */
        $bestScore = UserLevelScore::where('user_id', $user->id)
            ->where('game_category_id', $gameCategory->id)
            ->where('level', $levelNumber)
            ->first();

        $bestPoin = $bestScore?->poin ?? 0;
        $bestStars = $bestScore?->stars ?? 0;
        $bestCorrect = $bestScore?->correct_answers ?? 0;

        /*
         * ================================
         * UNLOCK LEVEL BERIKUTNYA
         * ================================
         *
         * Yang digunakan adalah BEST SCORE,
         * bukan skor percobaan terakhir.
         */
        if ($bestPoin >= self::MINIMUM_PASSING_POINT) {

            $userProgress = UserProgress::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'game_category_id' => $gameCategory->id,
                ],
                [
                    'unlocked_level' => 1,
                ]
            );

            $nextLevel = $levelNumber + 1;

            /*
             * Jika next level masih tersedia,
             * buka level tersebut.
             */
            if (
                $nextLevel <= $gameCategory->jumlah_level &&
                $nextLevel > $userProgress->unlocked_level
            ) {
                $userProgress->update([
                    'unlocked_level' => $nextLevel,
                ]);
            }

            /*
             * Jika level terakhir selesai,
             * unlocked_level menjadi jumlah_level + 1.
             *
             * Ini digunakan agar sistem mengetahui
             * bahwa seluruh level telah selesai.
             */
            if (
                $levelNumber === $gameCategory->jumlah_level &&
                $userProgress->unlocked_level <= $gameCategory->jumlah_level
            ) {
                $userProgress->update([
                    'unlocked_level' => $gameCategory->jumlah_level + 1,
                ]);
            }
        }

        /*
         * ================================
         * UPDATE TOTAL POIN
         * ================================
         *
         * Total poin = jumlah nilai terbaik
         * dari setiap level.
         */
        DB::transaction(function () use ($user) {
            $this->recalculateTotalPoints($user);
        });

        /*
         * Bersihkan session percobaan.
         */
        session()->forget([
            $currentQuestionKey,
            $answersKey,
            "quiz_attempt_{$slug}_level_{$levelNumber}",
        ]);

        /*
         * Hasil percobaan sekarang.
         */
        if ($poin >= self::MINIMUM_PASSING_POINT) {
            $message = "Level selesai! Kamu mendapatkan {$stars} bintang dan {$poin} poin.";
            $messageType = 'success';
        } else {
            $message = "Level selesai. Kamu mendapatkan {$stars} bintang dan {$poin} poin. Minimal 18 poin untuk membuka level berikutnya.";
            $messageType = 'error';
        }

        /*
         * Apakah level berikutnya tersedia?
         */
        $nextLevel = $levelNumber + 1;

        $hasNextLevel =
            $nextLevel <= $gameCategory->jumlah_level &&
            $bestPoin >= self::MINIMUM_PASSING_POINT;

        /*
         * Redirect ke halaman hasil level.
         */
        return redirect()
            ->route('game.level.complete', [
                'slug' => $slug,
                'level' => $levelNumber,
            ])
            ->with('level_complete', true)
            ->with($messageType, $message)
            ->with('pembahasan', $pembahasan)
            ->with('attempt_correct', $correctAnswers)
            ->with('attempt_poin', $poin)
            ->with('attempt_stars', $stars)
            ->with('best_correct', $bestCorrect)
            ->with('best_poin', $bestPoin)
            ->with('best_stars', $bestStars)
            ->with('is_new_best', $isNewBestScore);
    }

    /**
     * Halaman hasil setelah menyelesaikan level.
     */
    public function levelComplete($slug, $level)
    {
        $gameCategory = GameCategory::where('slug', $slug)->firstOrFail();

        if ($gameCategory->status === 'maintenance') {
            return view('customer.maintenance', compact('gameCategory'));
        }

        $levelNumber = (int) $level;

        /*
         * Pastikan level valid.
         */
        if (
            $levelNumber < 1 ||
            $levelNumber > $gameCategory->jumlah_level
        ) {
            return redirect()
                ->route('game.levels', $gameCategory->slug)
                ->with('error', 'Level tidak ditemukan.');
        }

        /*
         * Ambil hasil percobaan dari session.
         */
        $attemptCorrect = (int) session('attempt_correct', 0);
        $attemptPoin = (int) session('attempt_poin', 0);
        $attemptStars = (int) session('attempt_stars', 0);

        /*
         * Ambil nilai terbaik dari database.
         */
        $bestScore = UserLevelScore::where('user_id', auth()->id())
            ->where('game_category_id', $gameCategory->id)
            ->where('level', $levelNumber)
            ->first();

        $bestCorrect = $bestScore?->correct_answers ?? $attemptCorrect;
        $bestPoin = $bestScore?->poin ?? $attemptPoin;
        $bestStars = $bestScore?->stars ?? $attemptStars;

        /*
         * Jumlah soal selalu 10.
         */
        $totalQuestions = self::QUESTIONS_PER_LEVEL;

        /*
         * Level berikutnya.
         */
        $nextLevel = $levelNumber + 1;

        /*
         * Boleh lanjut hanya jika:
         * - masih ada level berikutnya
         * - best score minimal 18
         */
        $hasNextLevel =
            $nextLevel <= $gameCategory->jumlah_level &&
            $bestPoin >= self::MINIMUM_PASSING_POINT;

        return view('customer.level_complete', [
            'gameCategory' => $gameCategory,
            'levelNumber' => $levelNumber,

            'totalQuestions' => $totalQuestions,

            'attemptCorrect' => $attemptCorrect,
            'attemptPoin' => $attemptPoin,
            'attemptStars' => $attemptStars,

            'bestCorrect' => $bestCorrect,
            'bestPoin' => $bestPoin,
            'bestStars' => $bestStars,

            'nextLevel' => $nextLevel,
            'hasNextLevel' => $hasNextLevel,

            'isNewBest' => session('is_new_best', false),
        ]);
    }
}