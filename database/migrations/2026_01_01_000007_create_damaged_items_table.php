<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('damaged_items', function (Blueprint $table) {
            $table->id();
            $table->string('damage_code')->unique()->comment('DMG-001, ...');
            $table->foreignId('rental_id')->constrained('rentals')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items')->restrictOnDelete();
            $table->foreignId('return_id')->nullable()->constrained('returns')->cascadeOnDelete();
            $table->enum('damage_level', ['minor', 'heavy', 'lost'])->default('minor');
            $table->text('description');
            $table->unsignedBigInteger('repair_cost')->default(0);
            $table->string('photo_path')->nullable();
            $table->timestamps();

            $table->index(['rental_id', 'item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('damaged_items');
    }
};
