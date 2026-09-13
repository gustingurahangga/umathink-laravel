<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardadminController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('customer.dashboard');
});

Route::get('/tentang-kami', function () {
    return view('customer.aboutus');
});

Route::get('/login', fn() => view('auth.halamanlogin'))->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Forgot Password Routes
Route::get('/forgot-password', [\App\Http\Controllers\ForgotPasswordController::class, 'showEmailForm'])->name('forgot.email.form');
Route::post('/forgot-password', [\App\Http\Controllers\ForgotPasswordController::class, 'sendOtp'])->name('forgot.email.submit');
Route::get('/forgot-password/verify/{unique_id}', [\App\Http\Controllers\ForgotPasswordController::class, 'showVerifyForm'])->name('forgot.verify.form');
Route::post('/forgot-password/verify/{unique_id}', [\App\Http\Controllers\ForgotPasswordController::class, 'checkOtp'])->name('forgot.verify.submit');
Route::get('/reset-password/{unique_id}', [\App\Http\Controllers\ForgotPasswordController::class, 'showResetForm'])->name('forgot.reset.form');
Route::post('/reset-password/{unique_id}', [\App\Http\Controllers\ForgotPasswordController::class, 'updatePassword'])->name('forgot.reset.submit');

Route::get('/register', fn() => view('auth.halamanregister'))->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/auth-google-redirect', [AuthController::class, 'google_redirect']);
Route::get('/auth-google-callback', [AuthController::class, 'google_callback']);

Route::get('/customer/profile', function () {
    return view('customer.profile');
})->middleware('auth')->name('customer.profile');

Route::post('/customer/profile/update', function (Illuminate\Http\Request $request) {
    $user = auth()->user();
    $request->validate([
        'username' => 'required|string|max:255',
        'namalengkap' => 'required|string|max:255',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $user->username = $request->username;
    $user->namalengkap = $request->namalengkap;

    if ($request->hasFile('photo')) {
        $file = $request->file('photo');
        $filename = $file->hashName();
        $file->move(public_path('uploads/profiles'), $filename);
        $user->photo = '/uploads/profiles/' . $filename;
    }

    $user->save();

    return back()->with('success', 'Profile berhasil diperbarui!');
})->middleware('auth')->name('customer.profile.update');

// ==================================
//        GRUP MIDDLEWARE
// ==================================
Route::group(['middleware' => ['auth', 'check_role:customer', 'check_status']], function () {
    Route::get('/customer', function () {
        return view('customer.dashboard');
    });

    Route::get('/game-dashboard', function () {
        $games = \App\Models\GameCategory::all();
        
        $user = auth()->user();
        $userProgress = [];
        if ($user) {
            try {
                $progressRecords = \App\Models\UserProgress::where('user_id', $user->id)->get();
                foreach ($progressRecords as $progress) {
                    $userProgress[$progress->game_category_id] = $progress->unlocked_level;
                }
            } catch (\Exception $e) {
                // Abaikan
            }
        }
        
        $allOtherGamesFinished = true;
        foreach ($games as $game) {
            $unlocked = $userProgress[$game->id] ?? 1;
            $game->completed_levels = max(0, min($unlocked - 1, $game->jumlah_level));
            
            if (strtolower($game->nama_game) !== 'tryout' && strtolower($game->slug) !== 'tryout') {
                if ($game->completed_levels < $game->jumlah_level) {
                    $allOtherGamesFinished = false;
                }
            }
        }

        foreach ($games as $game) {
            if (strtolower($game->nama_game) === 'tryout' || strtolower($game->slug) === 'tryout') {
                $game->is_locked = !$allOtherGamesFinished;
            }
        }

        return view('customer.game_dashboard', compact('games'));
    })->name('game.dashboard');

    Route::get('/games/{slug}/levels', function ($slug) {
        $game = \App\Models\GameCategory::where('slug', $slug)->firstOrFail();

        if ($game->status === 'maintenance') {
            return view('customer.maintenance', compact('game'));
        }

        $user = auth()->user();

        // Pengecekan khusus untuk Tryout
        if (strtolower($game->nama_game) === 'tryout' || strtolower($game->slug) === 'tryout') {
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

        // Level 1 selalu terbuka
        $unlockedLevels = 1;
        
        $user = auth()->user();
        if ($user) {
            try {
                $userProgress = \App\Models\UserProgress::where('user_id', $user->id)
                    ->where('game_category_id', $game->id)
                    ->first();
                if ($userProgress) {
                    $unlockedLevels = $userProgress->unlocked_level;
                }
            } catch (\Exception $e) {
                // Abaikan error
            }
        }

        $completedLevels = max(0, min($unlockedLevels - 1, $game->jumlah_level));

        return view('customer.levels', [
            'game' => $game,
            'totalLevels' => $game->jumlah_level,
            'unlockedLevels' => $unlockedLevels,
            'completedLevels' => $completedLevels
        ]);
    })->name('game.levels');

    Route::get('/games/{slug}/level/{level}/complete', [\App\Http\Controllers\GamePlayController::class, 'levelComplete'])->name('game.level.complete');
    Route::get('/games/{slug}/level/{level}/{question?}', [\App\Http\Controllers\GamePlayController::class, 'play'])->name('game.play');
    Route::post('/games/check', [\App\Http\Controllers\GamePlayController::class, 'check'])->name('game.check');



    Route::get('/leaderboard', function () {
        $users = \App\Models\User::where('role', 'customer')
            ->get()
            ->sortByDesc('total_poin')
            ->take(10)
            ->values();

        // Hitung sisa hari season (90 hari)
        $seasonStartedAt = \App\Models\Setting::getValue('season_started_at');
        if (!$seasonStartedAt) {
            $seasonStartedAt = now()->toDateTimeString();
            \App\Models\Setting::setValue('season_started_at', $seasonStartedAt);
        }
        
        $start = \Carbon\Carbon::parse($seasonStartedAt);
        $end = $start->copy()->addDays(90);
        $seasonDaysLeft = now()->greaterThanOrEqualTo($end) ? 0 : (int) now()->diffInDays($end);

        $latestSeason = \App\Models\SeasonHistory::max('season_number') ?? 0;
        $currentSeason = $latestSeason + 1;

        return view('customer.leaderboard', compact('users', 'seasonDaysLeft', 'currentSeason'));
    })->name('game.leaderboard');
});
// ==================================
//        GRUP ADMIN
// ==================================
Route::group(['middleware' => ['auth', 'check_role:admin']], function () {
    Route::get('/admin', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::get('/admin/dashboard', [DashboardadminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/api/recent-activity', [DashboardadminController::class, 'recentActivity'])->name('admin.api.recent-activity');

    Route::post('/admin/season/reset', function () {
        $latestSeason = \App\Models\SeasonHistory::max('season_number') ?? 0;
        $currentSeason = $latestSeason + 1;

        $topUsers = \App\Models\User::where('role', 'customer')
            ->orderBy('total_poin', 'desc')
            ->take(3)
            ->get();

        $rank = 1;
        foreach ($topUsers as $user) {
            if ($user->total_poin > 0) {
                \App\Models\SeasonHistory::create([
                    'user_id' => $user->id,
                    'season_number' => $currentSeason,
                    'peringkat' => $rank,
                    'poin' => $user->total_poin,
                    'liga' => $user->liga,
                ]);
            }
            $rank++;
        }

        \App\Models\User::where('role', 'customer')->update(['total_poin' => 0]);
        \App\Models\UserProgress::truncate();

        // Catat tanggal mulai season baru (untuk hitungan mundur 90 hari)
        \App\Models\Setting::setValue('season_started_at', now()->toDateTimeString());

        return redirect()->back()->with('success', "Season $currentSeason berhasil diakhiri! Season baru telah dimulai, poin & progress level di-reset.");
    })->name('admin.season.reset');

    Route::get('/admin/users', function () {
        $users = \App\Models\User::where('role', 'customer')->get();
        $totalPengguna = $users->count();
        return view('admin.manage_users', compact('users', 'totalPengguna'));
    })->name('admin.users');

    Route::put('/admin/users/{id}', function (\Illuminate\Http\Request $request, $id) {
        $request->validate([
            'namalengkap' => 'required',
            'username' => 'required|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
        ]);

        $user = \App\Models\User::findOrFail($id);
        $user->namalengkap = $request->namalengkap;
        $user->username = $request->username;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->save();
        return redirect()->back()->with('success', 'Pengguna berhasil diperbarui!');
    })->name('admin.users.update');

    Route::post('/admin/users', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'namalengkap' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = new \App\Models\User();
        $user->namalengkap = $request->namalengkap;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role = 'customer';
        $user->status = 'active';
        $user->save();
        return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan!');
    })->name('admin.users.store');

    Route::delete('/admin/users/{id}', function ($id) {
        $user = \App\Models\User::findOrFail($id);
        $user->delete();
        return redirect()->back();
    })->name('admin.users.delete');

    Route::get('/admin/games', function () {
        $games = \App\Models\GameCategory::all();
        $totalGame = $games->count();
        return view('admin.manage_games', compact('games', 'totalGame'));
    })->name('admin.games');

    Route::post('/admin/games', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'nama_game' => 'required|unique:game_categories,nama_game',
            'jumlah_level' => 'required|integer|min:1',
        ]);

        \App\Models\GameCategory::create([
            'nama_game' => $request->nama_game,
            'jumlah_level' => $request->jumlah_level,
        ]);

        return redirect()->back()->with('success', 'Game berhasil ditambahkan!');
    })->name('admin.games.store');

    Route::put('/admin/games/{id}', function (\Illuminate\Http\Request $request, $id) {
        $request->validate([
            'nama_game' => 'required|unique:game_categories,nama_game,' . $id,
            'jumlah_level' => 'required|integer|min:1',
        ]);

        $game = \App\Models\GameCategory::findOrFail($id);
        $game->update([
            'nama_game' => $request->nama_game,
            'jumlah_level' => $request->jumlah_level,
        ]);

        return redirect()->back()->with('success', 'Game berhasil diperbarui!');
    })->name('admin.games.update');

    Route::put('/admin/games/{id}/maintenance', function ($id) {
        $game = \App\Models\GameCategory::findOrFail($id);
        $game->status = $game->status === 'maintenance' ? 'active' : 'maintenance';
        $game->save();
        return redirect()->back()->with('success', 'Status game berhasil diubah!');
    })->name('admin.games.maintenance');

    Route::delete('/admin/games/{id}', function ($id) {
        $game = \App\Models\GameCategory::findOrFail($id);
        $game->delete();
        return redirect()->back()->with('success', 'Game berhasil dihapus!');
    })->name('admin.games.delete');

    // Kelola Soal (Questions Management)
    Route::get('/admin/questions', [\App\Http\Controllers\QuestionController::class, 'index'])->name('admin.questions');
    Route::post('/admin/questions/import', [\App\Http\Controllers\QuestionController::class, 'import'])->name('admin.questions.import');
    Route::get('/admin/questions/template', [\App\Http\Controllers\QuestionController::class, 'downloadTemplate'])->name('admin.questions.template');
    Route::put('/admin/questions/{id}', [\App\Http\Controllers\QuestionController::class, 'update'])->name('admin.questions.update');
    Route::delete('/admin/questions/destroy-all', [\App\Http\Controllers\QuestionController::class, 'destroyAll'])->name('admin.questions.destroyAll');
    Route::delete('/admin/questions/{id}', [\App\Http\Controllers\QuestionController::class, 'destroy'])->name('admin.questions.delete');
});
Route::group(['middleware' => ['auth', 'check_role:customer']], function () {
    Route::get('/verify', [VerificationController::class, 'index']);
    Route::post('/verify', [VerificationController::class, 'store']);
    Route::get('/verify/{unique_id}', [VerificationController::class, 'show']);
    Route::put('/verify/{unique_id}', [VerificationController::class, 'update']);
});




