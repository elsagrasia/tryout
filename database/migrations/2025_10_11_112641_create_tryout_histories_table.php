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
        Schema::create('tryout_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('tryout_package_id')->constrained('tryout_packages')->onDelete('cascade');
            $table->integer('total_questions');
            $table->integer('correct_count');
            $table->integer('wrong_count');
            $table->integer('unanswered_count')->default(0);
            $table->integer('score');
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tryout_histories');
    }
};
