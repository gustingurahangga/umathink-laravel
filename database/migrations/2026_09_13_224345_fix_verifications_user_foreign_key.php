<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek apakah foreign key sudah ada
        $exists = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'verifications'
              AND COLUMN_NAME = 'user_id'
              AND REFERENCED_TABLE_NAME = 'users'
              AND CONSTRAINT_NAME = 'verifications_user_id_foreign'
        ");

        // Hanya buat foreign key jika belum ada
        if (!$exists) {
            Schema::table('verifications', function (Blueprint $table) {
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        $exists = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'verifications'
              AND CONSTRAINT_NAME = 'verifications_user_id_foreign'
        ");

        if ($exists) {
            Schema::table('verifications', function (Blueprint $table) {
                $table->dropForeign('verifications_user_id_foreign');
            });
        }
    }
};