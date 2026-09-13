<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameCategory;
use App\Models\Question;
use App\Models\Answer;

class GamePlayController extends Controller
{
    /**
     * Menampilkan halaman play untuk level tertentu dengan navigasi soal
     */
    public function play($slug, $level, $questionNumber = 1)
    {
        // Cari GameCategory berdasarkan slug
        $gameCategory = GameCategory::where('slug', $slug)->firstOrFail();

        if ($gameCategory->status === 'maintenance') {
            return view('customer.maintenance', compact('gameCategory'));
        }

        $user = auth()->user();

        // Pengecekan khusus untuk Tryout
        if (strtolower($gameCategory->nama_game) === 'tryout' || strtolower($gameCategory->slug) === 'tryout') {
            $allGames = \App\Models\GameCategory::all();
            $allOtherGamesFinished = true;
            $userProgressAll = [];
            
            if ($user) {
                try {
                    $progressRecords = \App\Models\UserProgress::where('user_id', $user->id)->get();
                    foreach ($progressRecords as $progress) {
                        $userProgressAll[$progress->game_category_id] = $progress->unlocked_level;
                    }
                } catch (\Exception $e) {}
            }
            
            foreach ($allGames as $g) {
                if (strtolower($g->nama_game) !== 'tryout' && strtolower($g->slug) !== 'tryout') {
                    $unlocked = $userProgressAll[$g->id] ?? 1;
                    $completed = max(0, min($unlocked - 1, $g->jumlah_level));
                    if ($completed < $g->jumlah_level) {
                        $allOtherGamesFinished = false;
                        break;
                    }
                }
            }
            
            if (!$allOtherGamesFinished) {
                return redirect()->route('game.dashboard')->with('error', 'Selesaikan semua game lain terlebih dahulu untuk membuka Tryout!');
            }
        }

        $levelNumber = intval($level);
        $questionNumber = intval($questionNumber);

        $questions = collect();
        $currentQuestion = null;
        $answers = [];
        $totalQuestions = 0;
        $useFallback = false;

        $sessionKey = "current_question_{$slug}_level_{$levelNumber}";
        $expectedQuestionNumber = session($sessionKey, 1);

        
        if ($questionNumber < $expectedQuestionNumber) {
            return redirect()->route('game.play', ['slug' => $slug, 'level' => $levelNumber, 'question' => $expectedQuestionNumber])
                             ->with('error', 'Anda tidak dapat kembali ke soal sebelumnya!');
        }

       
        if ($questionNumber > $expectedQuestionNumber) {
            session([$sessionKey => $questionNumber]);
        }

   
        try {
            $questions = Question::where('game_category_id', $gameCategory->id)
                ->where('level', $levelNumber)
                ->orderBy('id', 'asc')
                ->get();

            if ($questions->isNotEmpty()) {
                $totalQuestions = $questions->count();

                // Validasi nomor soal
                if ($questionNumber < 1 || $questionNumber > $totalQuestions) {
                    $questionNumber = 1;
                }

                $currentQuestion = $questions[$questionNumber - 1];
                $answers = $currentQuestion->answers()->get();
            }
        } catch (\Exception $e) {
            // Abaikan error jika tabel belum dimigrasi
        }



        return view('customer.halamanplay', compact(
            'gameCategory',
            'levelNumber',
            'currentQuestion',
            'answers',
            'questionNumber',
            'totalQuestions'
        ));
    }

    /**
     * Memproses jawaban dan pindah ke soal berikutnya jika benar
     */
    public function check(Request $request)
    {
        $questionId = intval($request->input('question_id'));
        $selectedAnswerId = intval($request->input('selected_answer'));
        $slug = $request->input('slug');
        
        $gameCategory = GameCategory::where('slug', $slug)->first();
        if ($gameCategory && $gameCategory->status === 'maintenance') {
            return view('customer.maintenance', compact('gameCategory'));
        }

        $levelNumber = intval($request->input('level_number'));
        $questionNumber = intval($request->input('question_number'));
        $totalQuestions = intval($request->input('total_questions'));

        $isCorrect = false;

        // 1. Coba periksa di database
        try {
            $answerRecord = Answer::find($selectedAnswerId);
            if ($answerRecord) {
                $isCorrect = $answerRecord->is_correct;
            }
        } catch (\Exception $e) {
            // Abaikan error jika database belum siap
        }

        // 2. Fallback ke session (untuk data dinamis generator)
        if (!$isCorrect) {
            $correctSessionId = session("correct_answer_for_" . $questionId);
            if ($correctSessionId !== null && $selectedAnswerId === $correctSessionId) {
                $isCorrect = true;
            }
        }

        $poinBenar = intval($request->input('poin_benar', 10));
        $poinSalah = intval($request->input('poin_salah', 5));

        /** @var \App\Models\User|null $user */
        $user = auth()->user();
        
        // Simpan progress soal yang sudah dijawab benar di session
        $progressKey = "progress_{$slug}_level_{$levelNumber}";
        $progress = session($progressKey, []);
        $alreadyAnswered = in_array($questionNumber, $progress);
        
        $message = "";
        $messageType = "";

        if ($isCorrect) {
            if (!$alreadyAnswered) {
                // Tambah poin pengguna
                if ($user) {
                    try {
                        $user->total_poin = ($user->total_poin ?? 0) + $poinBenar;
                        
                        if ($user->total_poin > ($user->highest_poin ?? 0)) {
                            $user->highest_poin = $user->total_poin;
                            $user->highest_liga = $user->liga;
                        }
                        
                        $currentPeringkat = $user->peringkat;
                        if (is_numeric($currentPeringkat)) {
                            if (is_null($user->highest_peringkat) || $currentPeringkat < $user->highest_peringkat) {
                                $user->highest_peringkat = $currentPeringkat;
                            }
                        }

                        $user->save();
                    } catch (\Exception $ex) {
                        // Abaikan jika kolom total_poin belum ada
                    }
                }
                
                // Tambahkan ke progress
                $progress[] = $questionNumber;
                session([$progressKey => $progress]);
            }

            $poinMsg = $alreadyAnswered ? '' : " (+{$poinBenar} Poin)";
            $message = "Benar! 🎉 Lanjut ke soal berikutnya.{$poinMsg}";
            $messageType = 'success';
        } else {
            // Kurangi poin pengguna saat salah menjawab
            if ($user && $poinSalah > 0 && !$alreadyAnswered) {
                try {
                    $user->total_poin = max(0, ($user->total_poin ?? 0) - $poinSalah);
                    $user->save();
                } catch (\Exception $ex) {
                    // Abaikan jika kolom total_poin belum ada
                }
                
                // Tandai sebagai dijawab agar poin tidak terus berkurang, dan agar adil karena pindah ke soal berikutnya
                $progress[] = $questionNumber;
                session([$progressKey => $progress]);
            }

            $poinMsg = $alreadyAnswered ? '' : " (-{$poinSalah} Poin)";
            if ($selectedAnswerId == 0) {
                $message = "Waktu Habis! Anda tidak memilih jawaban.{$poinMsg} Lanjut ke soal berikutnya.";
            } else {
                $message = "Yah! Jawaban Salah.{$poinMsg} Lanjut ke soal berikutnya.";
            }
            $messageType = 'error';
        }

        // Ambil pembahasan soal saat ini
        $pembahasan = null;
        try {
            $currentQuestionModel = Question::find($questionId);
            if ($currentQuestionModel && !empty($currentQuestionModel->pembahasan)) {
                $pembahasan = $currentQuestionModel->pembahasan;
            }
        } catch (\Exception $e) {}

        // Set soal berikutnya di session agar tidak bisa kembali
        $sessionKey = "current_question_{$slug}_level_{$levelNumber}";
        session([$sessionKey => $questionNumber + 1]);

        // Cek apakah masih ada soal berikutnya
        if ($questionNumber < $totalQuestions) {
            // Pindah ke soal berikutnya
            $nextQuestion = $questionNumber + 1;
            return redirect()
                ->route('game.play', ['slug' => $slug, 'level' => $levelNumber, 'question' => $nextQuestion])
                ->with($messageType, $message)
                ->with('pembahasan', $pembahasan);
        } else {
            // Semua soal di level ini selesai
            
            // Hapus session current question karena level sudah selesai
            session()->forget($sessionKey);
            
            // Buka level berikutnya
            if ($user) {
                try {
                    $gameCategory = GameCategory::where('slug', $slug)->first();
                    if ($gameCategory) {
                        $userProgress = \App\Models\UserProgress::firstOrCreate(
                            ['user_id' => $user->id, 'game_category_id' => $gameCategory->id],
                            ['unlocked_level' => 1]
                        );
                        
                        $nextLevelToUnlock = $levelNumber + 1;
                        
                        if ($nextLevelToUnlock > $userProgress->unlocked_level && $nextLevelToUnlock <= $gameCategory->jumlah_level + 1) {
                            $userProgress->update(['unlocked_level' => $nextLevelToUnlock]);
                        }
                    }
                } catch (\Exception $e) {
                    // Abaikan jika tabel progress belum ada
                }
            }
            
            return redirect()
                ->route('game.level.complete', ['slug' => $slug, 'level' => $levelNumber])
                ->with('level_complete', true)
                ->with($messageType, $message)
                ->with('pembahasan', $pembahasan);
        }
    }

    /**
     * Menampilkan halaman selesai level
     */
    public function levelComplete($slug, $level)
    {
        $gameCategory = GameCategory::where('slug', $slug)->firstOrFail();

        if ($gameCategory->status === 'maintenance') {
            return view('customer.maintenance', compact('gameCategory'));
        }

        $levelNumber = intval($level);

        // Hitung total soal di level ini
        $totalQuestions = 0;
        try {
            $totalQuestions = Question::where('game_category_id', $gameCategory->id)
                ->where('level', $levelNumber)
                ->count();
        } catch (\Exception $e) {}

        if ($totalQuestions === 0) {
            $totalQuestions = 3; // fallback
        }

        // Hitung total poin
        $totalPoin = 0;
        try {
            $totalPoin = Question::where('game_category_id', $gameCategory->id)
                ->where('level', $levelNumber)
                ->sum('poin');
        } catch (\Exception $e) {}

        if ($totalPoin == 0) {
             // Fallback point calculations
             $totalPoin = $totalQuestions * 10;
        }

        // Cek apakah ada level berikutnya
        $nextLevel = $levelNumber + 1;
        $hasNextLevel = $nextLevel <= $gameCategory->jumlah_level;

        return view('customer.level_complete', compact(
            'gameCategory',
            'levelNumber',
            'totalQuestions',
            'totalPoin',
            'nextLevel',
            'hasNextLevel'
        ));
    }
}
