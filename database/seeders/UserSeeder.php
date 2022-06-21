<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       // Insert some stuff
        DB::table('users')->insert(
            array(
                'id' => 1,
                'firstname' => 'William',
                'lastname' => 'Castillo',
                'username' => 'William Castillo',
                'email' => 'admin@example.com',
                'password' => bcrypt('qwerty123'),
                'avatar' => 'no_avatar.png',
                'phone' => '0123456789',
                'role_id' => 1,
                'statut' => 1,
            )
        );

        DB::table('users')->insert(
            array(
                'id' => 2,
                'firstname' => 'User',
                'lastname' => 'Customer',
                'username' => 'User Customer',
                'email' => 'customer@example.com',
                'password' => bcrypt('qwerty123'),
                'avatar' => 'no_avatar.png',
                'phone' => '12345678',
                'role_id' => 2,
                'statut' => 1,
            )
        );
    }
}
