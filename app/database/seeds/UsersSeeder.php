<?php

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder {

	public function run()
	{
        $users = array();

        // Add your users in this form
//        array_push($users, array(
//            'username' => 'username',
//            'email' => 'email',
//            'password' => Hash::make('password'),
//        ));

		// Delete all users
		DB::table('users')->truncate();

        DB::table('users')->insert($users);
	}

}
