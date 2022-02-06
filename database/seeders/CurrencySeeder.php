<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Currency::insert([
            ['code' => 'USD','created_at' => date('Y-m-d')],
            ['code' => 'EUR','created_at' => date('Y-m-d')]
        ]);
    }
}
