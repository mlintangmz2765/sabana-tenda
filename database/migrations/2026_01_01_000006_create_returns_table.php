<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_code')->unique()->comment('RET-001, ...');
            $table->foreignId('rental_id')->unique()->constrained('rentals')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->comment('Staff yang memproses return');
            $table->date('actual_return_date');
            $table->unsignedSmallInteger('late_days')->default(0);
            $table->unsignedBigInteger('late_fee')->default(0);
            $table->unsignedBigInteger('damage_fee')->default(0);
            $table->unsignedBigInteger('total_fine')->default(0);
            $table->text('condition_check')->nullable();
            $table->enum('payment_status', ['unpaid', 'paid', 'waived'])->default('paid');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
