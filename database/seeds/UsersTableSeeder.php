<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'username'   => 'testuser1',
                'first_name' => 'Test',
                'last_name'  => 'User',
                'role'       => 0,
                'password'   => bcrypt('abcd1234')
            ],
            [
                'username'   => 'testadmin1',
                'first_name' => 'Test',
                'last_name'  => 'Admin',
                'role'       => 1,
                'password'   => bcrypt('abcd1234')
            ]
        ]);
    }
}
