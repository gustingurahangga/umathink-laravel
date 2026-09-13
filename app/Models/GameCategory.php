<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GameCategory extends Model
{
    protected $table = 'game_categories';

    protected $fillable = [
        'nama_game',
        'jumlah_level',
        'slug',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->nama_game);
            }
        });
        static::updating(function ($model) {
            $model->slug = Str::slug($model->nama_game);
        });
    }
}
