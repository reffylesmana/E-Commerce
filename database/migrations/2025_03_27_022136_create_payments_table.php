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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->string('payment_id');
            $table->enum('payment_method', [
                'bank_transfer', 
                'credit_card', 
                'e_wallet', 
                'qris', 
                'other'
            ]);
            $table->decimal('amount', 12, 2);
            $table->enum('status', [
                'pending', 
                'success', 
                'failed', 
                'expired', 
                'unpaid', 
                'refunded'
            ])->default('unpaid');
            $table->json('payment_details')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

