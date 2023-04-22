<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RatingsTableSeeder extends Seeder
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
                'entry_id' => rand(1, 10),
                'user_id' => rand(1, 10),
                'rating' => rand(1, 5),
            ];
        }

        DB::table('ratings')->insert($data);
    }
}
