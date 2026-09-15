<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLevelScore extends Model
{
    protected $table = 'user_level_scores';

    protected $fillable = [
        'user_id',
        'game_category_id',
        'level',
        'correct_answers',
        'poin',
        'stars',
    ];

    protected $casts = [
        'level' => 'integer',
        'correct_answers' => 'integer',
        'poin' => 'integer',
        'stars' => 'integer',
    ];

    /**
     * Relasi ke user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke game.
     */
    public function gameCategory()
    {
        return $this->belongsTo(GameCategory::class);
    }
}