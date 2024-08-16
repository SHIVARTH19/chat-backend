<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageSeeder extends Seeder
{
    public function run()
    {
        DB::table('messages')->insert([
            ['thread_id' => 1, 'sender_id' => 1, 'content' => 'Hello Bob!'],
            ['thread_id' => 1, 'sender_id' => 2, 'content' => 'Hi Alice!'],
            ['thread_id' => 2, 'sender_id' => 1, 'content' => 'Hey Charlie, let’s meet up!'],
            ['thread_id' => 2, 'sender_id' => 3, 'content' => 'Sure Alice, when?'],
            ['thread_id' => 3, 'sender_id' => 2, 'content' => 'Charlie, did you get the report?'],
            ['thread_id' => 3, 'sender_id' => 3, 'content' => 'Yes, I sent it yesterday.'],
        ]);
    }
}
