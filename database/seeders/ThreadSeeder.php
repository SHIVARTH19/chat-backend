<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThreadSeeder extends Seeder
{
    public function run()
    {
        DB::table('threads')->insert([
            ['id' => 1, 'sender_id' => 1, 'receiver_id' => 2],
            ['id' => 2, 'sender_id' => 1, 'receiver_id' => 3],
            ['id' => 3, 'sender_id' => 2, 'receiver_id' => 3],
        ]);
    }
}
