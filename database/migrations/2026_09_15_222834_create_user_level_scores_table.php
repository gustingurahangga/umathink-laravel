<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menyimpan nilai terbaik user untuk setiap level.
     */
    public function up(): void
    {
        Schema::create('user_level_scores', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('game_category_id')
                ->constrained('game_categories')
                ->onDelete('cascade');

            $table->unsignedInteger('level');

            $table->unsignedInteger('correct_answers')->default(0);

            $table->unsignedInteger('poin')->default(0);

            $table->unsignedTinyInteger('stars')->default(0);

            $table->timestamps();

            /*
             * Satu user hanya mempunyai satu
             * nilai terbaik untuk satu level dalam satu game.
             */
            $table->unique([
                'user_id',
                'game_category_id',
                'level'
            ]);
        });
    }

    /**
     * Reverse migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_level_scores');
    }
};