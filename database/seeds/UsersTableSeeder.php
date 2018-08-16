<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // these two users are needed so we have some known login credentials
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

        // these fake users are just for testing pagination
        $faker = Faker::create();
        for ($i = 0; $i < 50; $i++) {
            DB::table('users')->insert([
                'username'   => $faker->unique->username,
                'first_name' => $faker->firstName,
                'last_name'  => $faker->lastName,
                'role'       => rand(0,1),
                'password'   => bcrypt('abcd1234')
            ]);
        }
    }
}
