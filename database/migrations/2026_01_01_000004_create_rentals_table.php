<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->string('rental_code')->unique()->comment('RNT-2026-001, ...');
            $table->foreignId('customer_id')->constrained('customers')->restrictOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->comment('Staff yang input transaksi');
            $table->date('rental_date');
            $table->date('return_date')->comment('Tanggal kembali rencana');
            $table->unsignedSmallInteger('duration_days');
            $table->unsignedBigInteger('total_cost');
            $table->enum('rental_status', ['active', 'completed', 'late', 'cancelled'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'rental_status']);
            $table->index('rental_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
