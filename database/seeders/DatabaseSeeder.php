<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        DB::table('users')->insert([
            'name' => 'kurama',
            'email' => 'kurama@mail.com',
            'password' => Hash::make('kurama123')
        ]);

        DB::table('patients')->insert([
            'name' => 'Nabila',
            'gender' => 'Laki - laki',
            'phone' => '0856232345671',
            'email' => 'nabila@mail.com',
            'password' => Hash::make('nabila123')
        ]);
        
        DB::table('patients')->insert([
            'name' => 'Zidan Rizky Wijaya',
            'gender' => 'Laki - laki',
            'phone' => '0856232345671',
            'email' => 'zidan@mail.com',
            'password' => Hash::make('zidan123')
        ]);

        DB::table('admins')->insert([
            'name' => 'Satria Mandala',
            'gender' => 'Laki - laki',
            'phone' => '0856232345671',
            'email' => 'admin@mail.com',
            'password' => Hash::make('admin123')
        ]);

        DB::table('doctors')->insert([
            'name' => 'Budi',
            'gender' => 'Laki - laki',
            'specialization' => 'Heartwave',
            'phone' => '0856232345671',
            'email' => 'budi@mail.com',
            'password' => Hash::make('budi123')
        ]);

    }
}
