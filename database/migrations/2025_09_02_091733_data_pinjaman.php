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
        //
        Schema::create('data_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_botol');
            $table->string('nama_pelanggan');
            $table->date('tanggal_pinjaman');
            $table->string('penanggung_jawab');
            $table->date('tanggal_pengembalian')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
