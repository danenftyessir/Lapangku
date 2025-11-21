<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tambahkan 'company' ke enum user_type di tabel users
     */
    public function up(): void
    {
        // Untuk PostgreSQL, kita perlu mengubah kolom dengan raw SQL
        // karena Laravel tidak support alter enum directly

        // Drop constraint lama
        DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_user_type_check");

        // Recreate constraint dengan nilai baru
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_user_type_check CHECK (user_type IN ('student', 'institution', 'admin', 'company'))");
    }

    /**
     * Reverse the migrations.
     * Kembalikan ke enum tanpa 'company'
     */
    public function down(): void
    {
        // Hapus semua users dengan type 'company' sebelum rollback
        DB::table('users')->where('user_type', 'company')->delete();

        // Drop constraint yang ada
        DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_user_type_check");

        // Recreate constraint tanpa 'company'
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_user_type_check CHECK (user_type IN ('student', 'institution', 'admin'))");
    }
};
