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
        Schema::create('shipping', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('tracking_number')->nullable();
            $table->enum('shipping_method', [
                'jne', 
                'jnt', 
                'pos', 
                'sicepat', 
                'anteraja', 
                'other'
            ]);
            $table->enum('status', [
                'pending', 
                'shipped', 
                'in_transit', 
                'delivered', 
                'failed', 
                'returned'
            ])->default('pending');
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('shipping_address');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping');
    }
};

