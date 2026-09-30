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
        Schema::create('prospek', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('sepeda_motor_id')->constrained('sepeda_motor')->restrictOnDelete();
            $table->foreignId('sumber_prospek_id')->constrained('sumber_prospek')->restrictOnDelete();
            $table->foreignId('status_prospek_id')->constrained('status_prospek')->restrictOnDelete();

            $table->string('nama_konsumen', 100);
            $table->string('nomor_telepon', 20);
            $table->string('email', 150)->nullable();
            $table->text('alamat');
            $table->string('kelurahan', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('kota', 100)->default('Jakarta Timur');
            $table->string('provinsi', 100)->default('DKI Jakarta');
            $table->string('kode_pos', 10)->nullable();

            // Data Motor & Minat
            $table->string('warna_motor_diminati', 50)->nullable();
            $table->unsignedBigInteger('harga_otr')->default(0);
            $table->date('tanggal_follow_up_selanjutnya')->nullable();
            $table->text('catatan')->nullable();

            // Tahap & Data Go To Deal
            $table->enum('tahap_data', ['prospek', 'deal'])->default('prospek');
            $table->string('nomor_ktp', 30)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('pekerjaan', 100)->nullable();
            $table->enum('skema_pembelian', ['Cash', 'Kredit'])->nullable();
            $table->unsignedBigInteger('dp')->nullable();
            $table->unsignedSmallInteger('tenor_bulan')->nullable();
            $table->unsignedBigInteger('angsuran_per_bulan')->nullable();
            $table->string('leasing', 100)->nullable();
            $table->string('metode_pembayaran', 100)->nullable();
            $table->date('tanggal_deal')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['user_id', 'status_prospek_id']);
            $table->index('status_prospek_id');
            $table->index('tahap_data');
            $table->index('created_at');
            $table->index('nama_konsumen');
            $table->index('nomor_telepon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prospek');
    }
};
