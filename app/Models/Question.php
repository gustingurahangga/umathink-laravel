<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'game_category_id',
        'level',
        'teks_soal',
        'poin',
        'kurang_poin',
        'waktu',
        'pembahasan',
    ];

    /**
     * Relasi ke GameCategory
     */
    public function gameCategory(): BelongsTo
    {
        return $this->belongsTo(GameCategory::class, 'game_category_id');
    }

    /**
     * Relasi ke Answers (Jawaban pilihan ganda)
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'question_id');
    }
}
