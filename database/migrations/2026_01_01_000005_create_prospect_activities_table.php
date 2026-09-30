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
        Schema::create('aktivitas_prospek', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prospek_id')->constrained('prospek')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('status_prospek_id')->nullable()
                  ->constrained('status_prospek')->nullOnDelete();

            $table->enum('jenis_aktivitas', ['phone', 'visit']);
            $table->dateTime('waktu_aktivitas');
            $table->text('catatan');

            $table->timestamps();

            // History per prospect & admin monitoring indexes
            $table->index(['prospek_id', 'waktu_aktivitas']);
            $table->index(['jenis_aktivitas', 'waktu_aktivitas']);
            $table->index(['user_id', 'waktu_aktivitas']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas_prospek');
    }
};
