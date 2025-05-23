<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        
        DB::table('divisions')->insert([
            [
                'name' => 'IT',
            ],
            [
                'name' => 'HUMAS',
            ],
        ]);

        DB::table('users')->insert([
            [
                'name' => 'Farhan',
                'email' => 'refar03@gmail.com',
                'password' => bcrypt('password'),
                'div_id' => '1',
            ],
            [
                'name' => 'Fahreza',
                'email' => 'a@a.com',
                'password' => bcrypt('password'),
                'div_id' => '1',
            ],
            [
                'name' => 'Ramadhan',
                'email' => 'b@b.com',
                'password' => bcrypt('password'),
                'div_id' => '2',
            ],
        ]);
        
        DB::table('employees')->insert([
            [
                'name' => 'Farhan',
                'department' => 'Developer',
                'div_id' => '1',
            ],
        ]);
        
        DB::table('stationeries')->insert([
            [
                'name' => 'Mouse',
                'category' => 'Electronic',
                'initial_stock' => '100',
                'stock' => '100',
                'unit' => 'piece',
                'description' => 'Desc',
                'div_id' => '1',
            ],
            [
                'name' => 'Cable',
                'category' => 'Electronic',
                'initial_stock' => '200',
                'stock' => '200',
                'unit' => 'piece',
                'description' => 'Desc',
                'div_id' => '1',
            ],
            [
                'name' => 'Pulpen Joyko',
                'category' => 'Stationery',
                'initial_stock' => '50',
                'stock' => '50',
                'unit' => 'pack',
                'description' => 'Desc',
                'div_id' => '2',
            ],
        ]);

    }
}
