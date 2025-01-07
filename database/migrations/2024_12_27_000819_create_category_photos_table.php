<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryPhotosTable extends Migration
{
    public function up()
    {
        Schema::create('category_photos', function (Blueprint $table) {
            $table->id();
            $table->string('photo');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('category_photos');
    }
}
