<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProgress extends Model
{
    protected $fillable = [
        'user_id',
        'game_category_id',
        'unlocked_level',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gameCategory()
    {
        return $this->belongsTo(GameCategory::class);
    }
}
