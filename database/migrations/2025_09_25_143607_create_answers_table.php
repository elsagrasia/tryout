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
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('question_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->enum('selected_option', ['A','B','C','D','E']);
            $table->boolean('is_correct')->default(false);
            $table->timestamps();

            $table->unique(['attempt_id', 'question_id']); // 1 jawaban per soal per attempt
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
