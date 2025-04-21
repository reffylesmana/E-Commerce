<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEstimatedArrivalToShippingsTable extends Migration
{
    public function up()
    {
        Schema::table('shipping', function (Blueprint $table) {
            $table->dateTime('estimated_arrival')->nullable(); // Add the estimated arrival column
        });
    }

    public function down()
    {
        Schema::table('shipping', function (Blueprint $table) {
            $table->dropColumn('estimated_arrival'); // Remove the column if rolling back
        });
    }
}