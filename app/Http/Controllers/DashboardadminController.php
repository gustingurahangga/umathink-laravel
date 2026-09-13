<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardadminController extends Controller
{
    public function index()
    {
        $totalPengguna = \App\Models\User::where('role', 'customer')->count();
        $topPlayers = \App\Models\User::where('role', 'customer')
            ->get()
            ->sortByDesc('total_poin')
            ->take(7)
            ->values();

        $totalGame = \App\Models\GameCategory::count();
        $soalCount = \App\Models\Question::count();
        $totalLevel = \App\Models\GameCategory::sum('jumlah_level');

        // Ambil 10 aktivitas terbaru berdasarkan user_progress (user yang terakhir mengerjakan game)
        $activity = $this->getRecentActivity();

        $latestSeason = \App\Models\SeasonHistory::max('season_number') ?? 0;
        $currentSeason = $latestSeason + 1;

        return view('admin.dashboard', [
            'totalPengguna' => $totalPengguna,
            'totalGame' => $totalGame,
            'soalCount' => $soalCount,
            'totalLevel' => $totalLevel,
            'activity' => $activity,
            'topPlayers' => $topPlayers,
            'currentSeason' => $currentSeason
        ]);
    }

    /**
     * API endpoint untuk auto-refresh aktivitas terbaru
     */
    public function recentActivity()
    {
        $activity = $this->getRecentActivity();

        $data = $activity->map(function ($item) {
            return [
                'type' => $item->type,
                'username' => $item->username,
                'game' => $item->game,
                'level' => $item->level,
                'total_poin' => $item->total_poin,
                'updated_at' => $item->time_human,
            ];
        });

        return response()->json($data);
    }

    /**
     * Ambil 10 aktivitas terbaru dari tabel user_progress
     */
    private function getRecentActivity()
    {
        try {
            $progress = \App\Models\UserProgress::with(['user', 'gameCategory'])
                ->whereHas('user', function ($query) {
                    $query->where('role', 'customer');
                })
                ->orderBy('updated_at', 'desc')
                ->take(10)
                ->get()
                ->map(function($item) {
                    return (object) [
                        'type' => 'game_play',
                        'username' => $item->user->username ?? '-',
                        'game' => $item->gameCategory->nama_game ?? 'Belum Ada',
                        'level' => $item->unlocked_level,
                        'total_poin' => $item->user->total_poin ?? 0,
                        'time' => $item->updated_at ?? now(),
                        'time_human' => $item->updated_at ? $item->updated_at->diffForHumans() : 'Baru saja'
                    ];
                });

            $registrations = \App\Models\User::where('role', 'customer')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get()
                ->map(function($user) {
                    return (object) [
                        'type' => 'register',
                        'username' => $user->username,
                        'game' => 'Register Baru',
                        'level' => '-',
                        'total_poin' => $user->total_poin ?? 0,
                        'time' => $user->created_at ?? now(),
                        'time_human' => $user->created_at ? $user->created_at->diffForHumans() : 'Baru saja'
                    ];
                });

            $combined = $progress->concat($registrations)
                ->sortByDesc('time')
                ->take(10)
                ->values();

            return $combined;
        } catch (\Exception $e) {
            return collect();
        }
    }
}
