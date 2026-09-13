<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeasonHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'season_number',
        'peringkat',
        'poin',
        'liga',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
