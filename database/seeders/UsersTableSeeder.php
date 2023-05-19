<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        $data = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'johndoe@example.com',
                'password' => 'password',
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'email' => 'janedoe@example.com',
                'password' => 'password',
            ],
            [
                'first_name' => 'Bob',
                'last_name' => 'Smith',
                'email' => 'bobsmith@example.com',
                'password' => 'password',
            ],
            [
                'first_name' => 'Alice',
                'last_name' => 'Johnson',
                'email' => 'alicejohnson@example.com',
                'password' => 'password',
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Williams',
                'email' => 'davidwilliams@example.com',
                'password' => 'password',
            ],
            [
                'first_name' => 'Emily',
                'last_name' => 'Brown',
                'email' => 'emilybrown@example.com',
                'password' => 'password',
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Davis',
                'email' => 'michaeldavis@example.com',
                'password' => 'password',
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Wilson',
                'email' => 'sarahwilson@example.com',
                'password' => 'password',
            ],
            [
                'first_name' => 'Kevin',
                'last_name' => 'Lee',
                'email' => 'kevinlee@example.com',
                'password' => 'password',
            ],
            [
                'first_name' => 'Olivia',
                'last_name' => 'Taylor',
                'email' => 'oliviataylor@example.com',
                'password' => 'password',
            ],
        ];


        DB::table('users')->insert($data);
        */
        $user = new \App\Models\User;
        $user->first_name = 'John';
        $user->last_name = 'Doe';
        $user->email = 'test@test.at';
        $user->role = 'user';
        $user->password = bcrypt('password');
        $user->save();
    }
}
