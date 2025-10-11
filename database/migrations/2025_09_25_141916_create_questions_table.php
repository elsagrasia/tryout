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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();    
                  // paket tryout     
            $table->foreignId('category_id')
                  ->constrained()
                  ->onDelete('cascade');           // kategori = sistem/bidang
            $table->string('disease')->nullable();  // opsional: penyakit spesifik
            $table->longText('vignette');           // kasus/cerita
            $table->text('question_text');          // pertanyaan
            $table->text('option_a');
            $table->text('option_b');
            $table->text('option_c');
            $table->text('option_d');
            $table->text('option_e')->nullable();
            $table->enum('correct_option', ['A','B','C','D','E']);
            $table->longText('explanation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
