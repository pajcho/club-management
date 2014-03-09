<?php

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder {

	public function run()
	{
        $settings = array();

        array_push($settings, array(
            'title' => 'Site Title',
            'key' => 'site_title',
            'value' => 'GK DIF',
            'description' => '* This is used as site title inside browser',
        ));

        array_push($settings, array(
            'title' => 'Club Name',
            'key' => 'club_name',
            'value' => 'Gimnasticki klub "DIF"',
            'description' => '* This will be used when generating documents',
        ));

        array_push($settings, array(
            'title' => 'Club Address',
            'key' => 'club_address',
            'value' => 'Blagoja Parovica 144',
            'description' => '* This will be used when generating documents',
        ));

		// Delete all users
		DB::table('settings')->truncate();

        DB::table('settings')->insert($settings);
	}

}
