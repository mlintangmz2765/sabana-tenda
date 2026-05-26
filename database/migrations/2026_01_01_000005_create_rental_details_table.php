<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_details', function (Blueprint $table) {
            $table->id();
            $table->string('detail_code')->unique()->comment('RD-001, ...');
            $table->foreignId('rental_id')->constrained('rentals')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items')->restrictOnDelete();
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('price_per_day')->comment('Snapshot harga sewa saat transaksi');
            $table->unsignedBigInteger('subtotal');
            $table->timestamps();

            $table->index(['rental_id', 'item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_details');
    }
};
