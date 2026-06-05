<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jawaban_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kuis_id')->constrained('kuis')->cascadeOnDelete();
            $table->foreignId('soal_id')->constrained('soal_kuis')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('users')->cascadeOnDelete();
            $table->integer('attempt')->default(1);
            $table->text('jawaban')->nullable();
            $table->boolean('is_benar')->nullable();
            $table->decimal('poin_diperoleh', 5, 2)->default(0);
            $table->timestamps();
            $table->unique(['soal_id', 'siswa_id', 'attempt'], 'unique_jawaban_siswa');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawaban_siswa');
    }
};
