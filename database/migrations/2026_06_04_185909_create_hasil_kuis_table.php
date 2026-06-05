<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_kuis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kuis_id')->constrained('kuis')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('users')->cascadeOnDelete();
            $table->integer('attempt')->default(1);
            $table->decimal('nilai_raw', 5, 2)->default(0);
            $table->decimal('nilai_akhir', 5, 2)->default(0);
            $table->integer('benar')->default(0);
            $table->integer('salah')->default(0);
            $table->dateTime('mulai_at')->nullable();
            $table->dateTime('selesai_at')->nullable();
            $table->boolean('is_submitted')->default(false);
            $table->timestamps();
            $table->unique(['kuis_id', 'siswa_id', 'attempt'], 'unique_hasil_kuis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_kuis');
    }
};
