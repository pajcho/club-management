<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MemberGroupsSeeder extends Seeder {

	public function run()
	{
        $memberGroups = array();

        array_push($memberGroups, array(
            'name' => 'R1',
            'location' => 'DIF',
            'training' => '{"monday":{"start":"18:00","end":"19:00"},"tuesday":{"start":"","end":""},"wednesday":{"start":"18:00","end":"19:00"},"thursday":{"start":"","end":""},"friday":{"start":"","end":""},"saturday":{"start":"","end":""},"sunday":{"start":"","end":""}}',
        ));

        array_push($memberGroups, array(
            'name' => 'R2',
            'location' => 'DIF',
            'training' => '{"monday":{"start":"","end":""},"tuesday":{"start":"18:00","end":"19:00"},"wednesday":{"start":"","end":""},"thursday":{"start":"18:00","end":"19:00"},"friday":{"start":"","end":""},"saturday":{"start":"18:00","end":"19:00"},"sunday":{"start":"","end":""}}',
        ));

        array_push($memberGroups, array(
            'name' => 'R3',
            'location' => 'DIF',
            'training' => '{"monday":{"start":"","end":""},"tuesday":{"start":"","end":""},"wednesday":{"start":"18:00","end":"19:00"},"thursday":{"start":"","end":""},"friday":{"start":"18:00","end":"19:00"},"saturday":{"start":"","end":""},"sunday":{"start":"","end":""}}',
        ));

        array_push($memberGroups, array(
            'name' => 'R4',
            'location' => 'DIF',
        ));

        array_push($memberGroups, array(
            'name' => 'R5',
            'location' => 'Karadjordje',
        ));

        array_push($memberGroups, array(
            'name' => 'R6',
            'location' => 'Karadjordje',
        ));

        array_push($memberGroups, array(
            'name' => 'R7',
            'location' => 'Novi Beograd',
        ));

        array_push($memberGroups, array(
            'name' => 'R8',
            'location' => 'Novi Beograd',
        ));

		// Delete all users
		DB::table('member_groups')->truncate();

        DB::table('member_groups')->insert($memberGroups);
	}

}
