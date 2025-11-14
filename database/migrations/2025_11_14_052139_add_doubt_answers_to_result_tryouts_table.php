<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('result_tryouts', function (Blueprint $table) {
            $table->integer('doubt_answers')->default(0)->after('unanswered');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('result_tryouts', function (Blueprint $table) {
            $table->dropColumn('doubt_answers');
        });
    }
};
