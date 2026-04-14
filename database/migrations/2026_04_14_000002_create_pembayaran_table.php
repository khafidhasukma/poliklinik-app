<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_periksa')->constrained('periksa')->cascadeOnDelete();
            $table->foreignId('id_pasien')->constrained('users')->cascadeOnDelete();
            $table->string('bukti_pembayaran')->nullable();
            $table->enum('status', ['belum_bayar', 'menunggu_verifikasi', 'lunas'])->default('belum_bayar');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
