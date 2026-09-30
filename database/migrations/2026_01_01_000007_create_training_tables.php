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
        // 1. Trainings Table
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_training');
            $table->string('slug')->unique();
            $table->enum('kategori', ['product_knowledge', 'sales_skill'])->default('product_knowledge');
            $table->text('deskripsi');
            $table->string('estimasi_waktu')->default('15 Menit');
            $table->string('icon')->nullable();
            $table->enum('status', ['published', 'draft'])->default('published');
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // 2. Training Materials Table
        Schema::create('training_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained('trainings')->onDelete('cascade');
            $table->string('judul_materi');
            $table->longText('isi_materi');
            $table->string('media_url')->nullable();
            $table->integer('urutan')->default(1);
            $table->timestamps();
        });

        // 3. Training Quizzes Table
        Schema::create('training_quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained('trainings')->onDelete('cascade');
            $table->text('pertanyaan');
            $table->json('pilihan_jawaban'); // Array of choices: ["A. ...", "B. ...", "C. ...", "D. ..."]
            $table->string('jawaban_benar'); // Key / exact text / index of correct option
            $table->text('penjelasan')->nullable();
            $table->integer('urutan')->default(1);
            $table->timestamps();
        });

        // 4. User Training Progress Table
        Schema::create('user_training_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('training_id')->constrained('trainings')->onDelete('cascade');
            $table->json('completed_material_ids')->nullable();
            $table->integer('progress_persen')->default(0);
            $table->enum('status', ['belum_mulai', 'sedang_berjalan', 'selesai'])->default('belum_mulai');
            $table->unsignedBigInteger('last_material_id')->nullable();
            $table->timestamp('waktu_mulai')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'training_id']);
        });

        // 5. User Quiz Results Table
        Schema::create('user_quiz_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('training_id')->constrained('trainings')->onDelete('cascade');
            $table->integer('nilai')->default(0);
            $table->integer('jumlah_benar')->default(0);
            $table->integer('total_soal')->default(0);
            $table->boolean('status_lulus')->default(false);
            $table->json('jawaban_user')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_quiz_results');
        Schema::dropIfExists('user_training_progress');
        Schema::dropIfExists('training_quizzes');
        Schema::dropIfExists('training_materials');
        Schema::dropIfExists('trainings');
    }
};
