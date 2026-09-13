<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'namalengkap',
        'email',
        'google_id',
        'avatar',
        'role',
        'status',
        'password',
        'total_poin',
        'highest_poin',
        'highest_peringkat',
        'highest_liga',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    /**
     * Get the user's rank based on total_poin.
     */
    public function getPeringkatAttribute()
    {
        if ($this->role !== 'customer') return '-';
        
        $points = $this->total_poin ?? 0;
        // Hitung berapa banyak user dengan poin lebih tinggi
        $higherUsersCount = static::where('role', 'customer')
            ->where('total_poin', '>', $points)
            ->count();
            
        return $higherUsersCount + 1;
    }

    /**
     * Get the user's league based on total_poin.
     */
    public function getLigaAttribute()
    {
        $points = $this->total_poin ?? 0;
        
        if ($points >= 4201) return 'Immortal';
        if ($points >= 2601) return 'Legenda';
        if ($points >= 1201) return 'Amatir';

        return 'Bronze';
    }
}
    