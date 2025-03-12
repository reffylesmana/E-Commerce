<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPhoto;
use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::insert([
            [
                'name' => 'Admin Ganteng',
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'is_admin' => 1,
                'password' => bcrypt('admin123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'reffy lesmana',
                'username' => 'reffy',
                'email' => 'reffy@gmail.com',
                'role' => 'buyer',
                'is_admin' => 0,
                'password' => bcrypt('reffy123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'tataq bayu',
                'username' => 'tataq',
                'email' => 'tataq@gmail.com',
                'role' => 'seller',
                'is_admin' => 0,
                'password' => bcrypt('tataq123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

    }
}
