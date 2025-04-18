<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('seller_id')->nullable();  // Add this line if `seller_id` is missing
        });
    }
    
    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('seller_id');
        });
    }
    
};
