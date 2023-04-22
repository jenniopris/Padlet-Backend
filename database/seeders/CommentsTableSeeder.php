<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $data = [];

        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'entry_id' => rand(1, 10),
                'user_id' => rand(1, 10),
                'comment' => 'This is a sample comment',
            ];
        }

        DB::table('comments')->insert($data);
    }
}
