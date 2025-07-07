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
            $table->id(); // AUTO INCREMENT PRIMARY KEY
            $table->string('nama'); // kolom wajib
            $table->string('satuan')->nullable(); // bisa null
            $table->integer('stok_dipesan')->default(0); // default 0
            $table->integer('stok_tersedia')->default(0);
            $table->integer('stok_dibutuhkan')->default(0);
            $table->timestamps(); // created_at dan updated_at
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
