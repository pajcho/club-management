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
            'value' => 'Gimnasticki klub "DIF" - Beograd',
            'description' => '* This will be used when generating documents',
        ));

        array_push($settings, array(
            'title' => 'Club Address',
            'key' => 'club_address',
            'value' => 'Blagoja Parovica 144',
            'description' => '* This will be used when generating documents',
        ));

        array_push($settings, array(
            'title' => 'Number of fields per row on settings page',
            'key' => 'per_row',
            'value' => 2,
            'description' => '* This will change layout on this page',
        ));

        array_push($settings, array(
            'title' => '"Attendance Document" Title',
            'key' => 'att_doc_title',
            'value' => 'Evidencija pohadjanja treninga',
            'description' => '* This will be used when generating documents',
        ));

        array_push($settings, array(
            'title' => '"Attendance Document" Month Translation',
            'key' => 'att_doc_month_translation',
            'value' => 'Mesec',
            'description' => '* This will be used when generating documents',
        ));

        array_push($settings, array(
            'title' => '"Attendance Document" Name Translation',
            'key' => 'att_doc_name_translation',
            'value' => 'Ime i Prezime',
            'description' => '* This will be used when generating documents',
        ));

        array_push($settings, array(
            'title' => '"Attendance Document" Phone Translation',
            'key' => 'att_doc_phone_translation',
            'value' => 'Telefon',
            'description' => '* This will be used when generating documents',
        ));

		// Delete all users
		DB::table('settings')->truncate();

        DB::table('settings')->insert($settings);
	}

}
