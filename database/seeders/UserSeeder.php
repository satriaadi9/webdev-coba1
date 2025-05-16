<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // DB::table('users')->insert([
        //     [
        //         'name'=>'User',
        //         'email'=>'user@user.com',
        //         'password'=>Hash::make('user'),
        //     ],
        // ]);

        DB::table('users')->insert([
            [
                'name'=>'Admin',
                'email'=>'admin@admin.com',
                'password'=>Hash::make('admin'),
            ],
        ]);
    }
}
