<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom untuk pelacakan waktu tryout
     */
    public function up(): void
    {
        Schema::table('user_answers', function (Blueprint $table) {
            // Tambah hanya jika kolom belum ada (aman di server)
            if (!Schema::hasColumn('user_answers', 'start_time')) {
                $table->timestamp('start_time')->nullable()->after('is_correct');
            }

            if (!Schema::hasColumn('user_answers', 'elapsed_seconds')) {
                $table->integer('elapsed_seconds')->default(0)->after('start_time');
            }

            if (!Schema::hasColumn('user_answers', 'is_finished')) {
                $table->boolean('is_finished')->default(false)->after('elapsed_seconds');
            }
        });
    }

    /**
     * Rollback dengan aman (hapus hanya kalau kolom ada)
     */
    public function down(): void
    {
        Schema::table('user_answers', function (Blueprint $table) {
            if (Schema::hasColumn('user_answers', 'start_time')) {
                $table->dropColumn('start_time');
            }
            if (Schema::hasColumn('user_answers', 'elapsed_seconds')) {
                $table->dropColumn('elapsed_seconds');
            }
            if (Schema::hasColumn('user_answers', 'is_finished')) {
                $table->dropColumn('is_finished');
            }
        });
    }
};

