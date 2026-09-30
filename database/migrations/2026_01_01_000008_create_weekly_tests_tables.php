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
        // 1. Weekly Tests Table
        Schema::create('weekly_tests', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tes');
            $table->string('slug')->unique();
            $table->string('kategori')->default('Product Knowledge & Sales Skill');
            $table->text('deskripsi');
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->integer('durasi_menit')->default(15);
            $table->integer('nilai_minimum')->default(70);
            $table->enum('status', ['draft', 'published', 'archived'])->default('published');
            $table->timestamps();
        });

        // 2. Weekly Questions Table
        Schema::create('weekly_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('weekly_test_id')->constrained('weekly_tests')->onDelete('cascade');
            $table->text('pertanyaan');
            $table->text('pilihan_a');
            $table->text('pilihan_b');
            $table->text('pilihan_c');
            $table->text('pilihan_d');
            $table->enum('jawaban_benar', ['A', 'B', 'C', 'D'])->default('A');
            $table->text('penjelasan')->nullable();
            $table->integer('urutan')->default(1);
            $table->timestamps();
        });

        // 3. Weekly Test Attempts Table
        Schema::create('weekly_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('weekly_test_id')->constrained('weekly_tests')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('waktu_mulai')->useCurrent();
            $table->timestamp('waktu_selesai')->nullable();
            $table->integer('nilai')->default(0);
            $table->integer('jumlah_benar')->default(0);
            $table->integer('jumlah_salah')->default(0);
            $table->integer('total_soal')->default(0);
            $table->enum('status', ['sedang_mengerjakan', 'selesai', 'waktu_habis'])->default('sedang_mengerjakan');
            $table->boolean('status_lulus')->default(false);
            $table->timestamps();

            $table->unique(['weekly_test_id', 'user_id']);
        });

        // 4. Weekly Answers Table
        Schema::create('weekly_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('weekly_attempt_id')->constrained('weekly_attempts')->onDelete('cascade');
            $table->foreignId('weekly_question_id')->constrained('weekly_questions')->onDelete('cascade');
            $table->string('jawaban_user', 10)->nullable(); // 'A', 'B', 'C', 'D'
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_answers');
        Schema::dropIfExists('weekly_attempts');
        Schema::dropIfExists('weekly_questions');
        Schema::dropIfExists('weekly_tests');
    }
};
