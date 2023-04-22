<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DateTime;

class PadletsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            'First Padlet',
            'Second Padlet',
            'Third Padlet',
            'Fourth Padlet',
            'Fifth Padlet',
            'Sixth Padlet',
            'Seventh Padlet',
            'Eighth Padlet',
            'Ninth Padlet',
            'Tenth Padlet',
        ];

        $data = [];

        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'name' => $names[array_rand($names)],
                'is_public' => true,
                'user_id' => 1,
            ];
        }

        DB::table('padlets')->insert($data);
    }
}
