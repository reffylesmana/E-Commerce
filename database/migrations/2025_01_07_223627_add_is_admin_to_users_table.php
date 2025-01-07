<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsAdminToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom is_admin dengan tipe boolean
            $table->boolean('is_admin')->default(0); // Default 0, berarti bukan admin
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom is_admin jika rollback migration
            $table->dropColumn('is_admin');
        });
    }
}
