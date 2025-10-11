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
        Schema::table('questions', function (Blueprint $table) {
            $table->unsignedBigInteger('tryout_id')->after('id')->nullable();
            $table->foreign('tryout_id')->references('id')->on('tryout_packages')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['tryout_id']);
            $table->dropColumn('tryout_id');
        });
    }
};
