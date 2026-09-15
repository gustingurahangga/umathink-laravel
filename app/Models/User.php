<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'namalengkap',
        'email',
        'google_id',
        'avatar',
        'photo',
        'role',
        'status',
        'password',
        'total_poin',
        'highest_poin',
        'highest_peringkat',
        'highest_liga',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi nilai terbaik setiap level.
     */
    public function levelScores()
    {
        return $this->hasMany(UserLevelScore::class);
    }

    /**
     * Ranking berdasarkan total poin.
     *
     * Leaderboard TIDAK menggunakan season.
     */
    public function getPeringkatAttribute()
    {
        if ($this->role !== 'customer') {
            return '-';
        }

        $points = (int) ($this->total_poin ?? 0);

        $higherUsersCount = static::where('role', 'customer')
            ->where('total_poin', '>', $points)
            ->count();

        return $higherUsersCount + 1;
    }

    /**
     * Liga berdasarkan total poin.
     */
    public function getLigaAttribute()
    {
        $points = (int) ($this->total_poin ?? 0);

        if ($points >= 4201) {
            return 'Immortal';
        }

        if ($points >= 2601) {
            return 'Legenda';
        }

        if ($points >= 1201) {
            return 'Amatir';
        }

        return 'Bronze';
    }
}