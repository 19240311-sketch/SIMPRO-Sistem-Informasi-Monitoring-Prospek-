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
        Schema::create('angsuran_motor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sepeda_motor_id')->constrained('sepeda_motor')->cascadeOnDelete();
            $table->unsignedSmallInteger('tenor_bulan');
            $table->unsignedBigInteger('nominal_angsuran');
            $table->timestamps();

            $table->unique(['sepeda_motor_id', 'tenor_bulan']);
            $table->index('sepeda_motor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angsuran_motor');
    }
};
