<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->unique()->comment('ITM-001, ITM-002, ...');
            $table->foreignId('category_id')->constrained('categories')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('specifications')->nullable();
            $table->unsignedInteger('stock')->default(0)->comment('Total unit dimiliki');
            $table->unsignedInteger('available_stock')->default(0)->comment('Unit tersedia untuk disewa');
            $table->enum('condition', ['good', 'minor_damage', 'heavy_damage'])->default('good');
            $table->enum('status', ['available', 'unavailable'])->default('available');
            $table->unsignedBigInteger('price_per_day')->comment('Harga sewa per hari (Rp)');
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['category_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
