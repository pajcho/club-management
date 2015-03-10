<?php

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder {

	public function run()
	{
        $users = array();

        // Add default user but you can add any number of users in this format
        array_push($users, array(
            'first_name' => $_ENV['ADMIN_FIRSTNAME'],
            'last_name' => $_ENV['ADMIN_LASTNAME'],
            'username' => $_ENV['ADMIN_USERNAME'],
            'email' => $_ENV['ADMIN_EMAIL'],
            'password' => Hash::make($_ENV['ADMIN_PASSWORD']),
            'type' => 'admin', // can be 'admin' or 'trainer'
        ));

		// Delete all users
		DB::table('users')->truncate();

        DB::table('users')->insert($users);
	}

}
