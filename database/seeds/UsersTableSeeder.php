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
        factory(App\User::class, 5)->create();

        App\User::create([
        	'name' => 'Heriberto',
        	'email'=> 'hhr@mymail.com',
        	'password' => bcrypt('cofeeat5am')
        ]);
    }
}
