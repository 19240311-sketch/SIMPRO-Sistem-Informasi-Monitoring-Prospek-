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
        // 1. Upgrade table 'trainings'
        Schema::table('trainings', function (Blueprint $table) {
            if (!Schema::hasColumn('trainings', 'youtube_url')) {
                $table->string('youtube_url')->nullable()->after('deskripsi');
            }
            if (!Schema::hasColumn('trainings', 'youtube_video_id')) {
                $table->string('youtube_video_id', 100)->nullable()->after('youtube_url');
            }
            if (!Schema::hasColumn('trainings', 'thumbnail_url')) {
                $table->string('thumbnail_url')->nullable()->after('youtube_video_id');
            }
            if (!Schema::hasColumn('trainings', 'durasi_video')) {
                $table->string('durasi_video', 50)->nullable()->after('thumbnail_url');
            }
            if (!Schema::hasColumn('trainings', 'jumlah_soal')) {
                $table->integer('jumlah_soal')->default(5)->after('durasi_video');
            }
            if (!Schema::hasColumn('trainings', 'passing_grade')) {
                $table->integer('passing_grade')->default(80)->after('jumlah_soal');
            }
            if (!Schema::hasColumn('trainings', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('status');
            }
        });

        // 2. Upgrade table 'user_training_progress'
        Schema::table('user_training_progress', function (Blueprint $table) {
            if (!Schema::hasColumn('user_training_progress', 'video_progress')) {
                $table->integer('video_progress')->default(0)->after('progress_persen');
            }
            if (!Schema::hasColumn('user_training_progress', 'video_completed')) {
                $table->boolean('video_completed')->default(false)->after('video_progress');
            }
            if (!Schema::hasColumn('user_training_progress', 'waktu_mulai_video')) {
                $table->timestamp('waktu_mulai_video')->nullable()->after('video_completed');
            }
            if (!Schema::hasColumn('user_training_progress', 'waktu_selesai_video')) {
                $table->timestamp('waktu_selesai_video')->nullable()->after('waktu_mulai_video');
            }
            if (!Schema::hasColumn('user_training_progress', 'nilai_kuis')) {
                $table->integer('nilai_kuis')->nullable()->after('waktu_selesai_video');
            }
            if (!Schema::hasColumn('user_training_progress', 'jumlah_percobaan_kuis')) {
                $table->integer('jumlah_percobaan_kuis')->default(0)->after('nilai_kuis');
            }
            if (!Schema::hasColumn('user_training_progress', 'status_lulus')) {
                $table->boolean('status_lulus')->default(false)->after('jumlah_percobaan_kuis');
            }
            if (!Schema::hasColumn('user_training_progress', 'tanggal_terakhir_belajar')) {
                $table->timestamp('tanggal_terakhir_belajar')->nullable()->after('status_lulus');
            }
            if (!Schema::hasColumn('user_training_progress', 'tanggal_lulus')) {
                $table->timestamp('tanggal_lulus')->nullable()->after('tanggal_terakhir_belajar');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->dropColumn([
                'youtube_url',
                'youtube_video_id',
                'thumbnail_url',
                'durasi_video',
                'jumlah_soal',
                'passing_grade',
                'is_active',
            ]);
        });

        Schema::table('user_training_progress', function (Blueprint $table) {
            $table->dropColumn([
                'video_progress',
                'video_completed',
                'waktu_mulai_video',
                'waktu_selesai_video',
                'nilai_kuis',
                'jumlah_percobaan_kuis',
                'status_lulus',
                'tanggal_terakhir_belajar',
                'tanggal_lulus',
            ]);
        });
    }
};
