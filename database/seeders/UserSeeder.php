<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            [
                'name'              => 'User A',
                'email'             => 'a@gmail.com',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'password'          => Hash::make('12345678'),
                'balance'           => 50000,
                'currency_id'       => 1,
                'created_at'        => date('Y-m-d')
            ],
            [
                'name'              => 'User B',
                'email'             => 'b@gmail.com',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'password'          => Hash::make('12345678'),
                'balance'           => 50000,
                'currency_id'       => 2,
                'created_at'        => date('Y-m-d')
            ]
        ]);
    }
}
