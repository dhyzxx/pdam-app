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
        Schema::create('pemakaian_airs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pelanggans')->onDelete('cascade');
            $table->string('bulan'); // Format YYYY-MM, contoh: 2024-05
            $table->decimal('meter_awal', 8, 2);
            $table->decimal('meter_akhir', 8, 2);
            $table->decimal('volume_pemakaian', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemakaian_airs');
    }
};