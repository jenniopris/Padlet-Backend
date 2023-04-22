<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];

        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'padlet_id' => rand(1, 10),
                'user_id' => rand(1, 10),
                'name' => 'Sample Text Entry',
                'type' => 'text',
                'content' => 'This is a sample text entry',
            ];
        }

        DB::table('entries')->insert($data);
    }
}
