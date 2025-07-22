<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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

        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->syncPermissions(Permission::all());
        
        // DB::table('divisions')->insert([
        //     [
        //         'name' => 'IT',
        //     ],
        //     [
        //         'name' => 'HUMAS',
        //     ],
        // ]);

        $divisi = [
            'IT', 'PR', 'HR', 'Sales', 'Marketing', 'Production', 'Accounting',
            'Finance', 'Purchasing', 'QC', 'HSE'
        ];

        foreach ($divisi as $name) {
            DB::table('divisions')->insert([
                'name' => $name
            ]);
        }

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

        User::where('email', 'refar03@gmail.com')->first()?->assignRole('Super Admin');
        
        DB::table('employees')->insert([
            [
                'name' => 'Farhan',
                'department' => 'Developer',
                'div_id' => '1',
            ],
        ]);
        
        // DB::table('stationeries')->insert([
        //     [
        //         'name' => 'Mouse',
        //         'category' => 'Electronic',
        //         'initial_stock' => '100',
        //         'stock' => '100',
        //         'unit' => 'piece',
        //         'description' => 'Desc',
        //         'div_id' => '1',
        //     ],
        //     [
        //         'name' => 'Cable',
        //         'category' => 'Electronic',
        //         'initial_stock' => '200',
        //         'stock' => '200',
        //         'unit' => 'piece',
        //         'description' => 'Desc',
        //         'div_id' => '1',
        //     ],
        //     [
        //         'name' => 'Pulpen Joyko',
        //         'category' => 'Stationery',
        //         'initial_stock' => '50',
        //         'stock' => '50',
        //         'unit' => 'pack',
        //         'description' => 'Desc',
        //         'div_id' => '2',
        //     ],
        // ]);


        $items = [
            'Kertas A4 HVS', 'Map Plastik', 'Map Coklat', 'Paper Clip', 'Paper Clamp',
            'Sticky Notes', 'Stabilo', 'Spidol Hitam', 'Spidol Biru', 'Spidol Merah',
            'Amplop', 'Stapler', 'Isi Stapler', 'Lem Kertas', 'Cap', 'Tinta Cap',
            'Tinta Printer', 'Document Holder', 'Gunting', 'Cutter', 'Isi Cutter',
            'Pulpen Pilot Hitam', 'Pulpen Pilot Biru', 'Pulpen Pilot Merah',
            'Label', 'Folder File', 'Perforator', 'Mouse', 'Laser Pointer', 'Tisu',
            'Flashdisk'
        ];

        $categories = ['ATK', 'Elektronik', 'Cetakan', 'Aksesoris'];
        $units = ['pcs', 'box', 'rim', 'pak'];

        $divisi = DB::table('divisions')->get();

        foreach ($divisi as $div) {
            foreach ($items as $item) {
                $stock = rand(100, 500);

                DB::table('stationeries')->insert([
                    'name' => $item,
                    'category' => $categories[array_rand($categories)],
                    'initial_stock' => $stock,
                    'stock' => $stock,
                    'unit' => $units[array_rand($units)],
                    'description' => 'Deskripsi untuk ' . $item,
                    'div_id' => $div->id,
                ]);
            }
        }
    }
}
