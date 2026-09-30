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
        Schema::create('sepeda_motor', function (Blueprint $table) {
            $table->id();
            $table->string('merk', 50)->default('Yamaha');
            $table->string('nama_model', 100);
            $table->string('varian', 50)->default('Standard');
            $table->unsignedBigInteger('harga_otr')->default(0);
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();

            $table->index(['nama_model', 'status_aktif']);
        });

        Schema::create('warna_motor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sepeda_motor_id')->constrained('sepeda_motor')->cascadeOnDelete();
            $table->string('nama_warna', 50);
            $table->string('kode_hex', 20)->nullable();
            $table->timestamps();

            $table->index('sepeda_motor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warna_motor');
        Schema::dropIfExists('sepeda_motor');
    }
};
