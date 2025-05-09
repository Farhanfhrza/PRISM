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

        DB::table('users')->insert([
            [
                'name' => 'Farhan',
                'email' => 'refar03@gmail.com',
                // 'email_verified_at' => now(),
                'password' => bcrypt('password'),
            ],
        ]);
        
        DB::table('divisions')->insert([
            [
                'name' => 'IT',
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
                'stock' => '100',
                'description' => 'Desc',
                'div_id' => '1',
            ],
            [
                'name' => 'Cable',
                'category' => 'Electronic',
                'stock' => '200',
                'description' => 'Desc',
                'div_id' => '1',
            ],
        ]);

    }
}
