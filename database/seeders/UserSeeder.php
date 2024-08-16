<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            ['id' => 1, 'nickname' => 'Alice'],
            ['id' => 2, 'nickname' => 'Bob'],
            ['id' => 3, 'nickname' => 'Charlie'],
        ]);
    }
}
