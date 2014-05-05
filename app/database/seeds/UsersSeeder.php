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
//            'first_name' => 'first_name',
//            'last_name' => 'last_name',
//            'username' => 'username',
//            'email' => 'email',
//            'password' => Hash::make('password'),
//            'type' => 'admin', // can be 'admin' or 'trainer'
//        ));

		// Delete all users
		DB::table('users')->truncate();

        DB::table('users')->insert($users);
	}

}
