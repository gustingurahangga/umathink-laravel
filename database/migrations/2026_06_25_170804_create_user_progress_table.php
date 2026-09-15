<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_progress', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('game_category_id')
                ->constrained()
                ->onDelete('cascade');

            /*
             * Level berikutnya yang boleh dimainkan.
             *
             * Contoh:
             * 1 = hanya level 1
             * 2 = level 1-2
             * 3 = level 1-3
             *
             * Jika semua level selesai:
             * jumlah_level + 1
             */
            $table->integer('unlocked_level')
                ->default(1);

            $table->timestamps();

            $table->unique([
                'user_id',
                'game_category_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};