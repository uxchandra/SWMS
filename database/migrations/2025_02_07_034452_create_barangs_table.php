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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('kode')->unique();
            $table->string('nama_barang');
            $table->foreignId('jenis_id');
            $table->string('size');
            $table->integer('stok_minimum');
            $table->integer('stok_maximum');
            $table->integer('stok')->nullable()->default(0);
            $table->string('nama_supplier');
            $table->string('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
