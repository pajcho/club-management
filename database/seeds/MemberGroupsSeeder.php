<?php

    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\DB;

    class MemberGroupsSeeder extends Seeder
    {
        public function run()
        {
            // Delete all users
            DB::table('member_groups')->truncate();

            $memberGroup = app()->make('App\Modules\Members\Repositories\MemberGroupRepositoryInterface');

            $memberGroup->create([
                'name'     => 'R1',
                'location' => 'DIF',
                'training' => '{"monday":{"start":"18:00","end":"19:00"},"tuesday":{"start":"","end":""},"wednesday":{"start":"18:00","end":"19:00"},"thursday":{"start":"","end":""},"friday":{"start":"","end":""},"saturday":{"start":"","end":""},"sunday":{"start":"","end":""}}',
            ]);

            $memberGroup->create([
                'name'     => 'R2',
                'location' => 'DIF',
                'training' => '{"monday":{"start":"","end":""},"tuesday":{"start":"18:00","end":"19:00"},"wednesday":{"start":"","end":""},"thursday":{"start":"18:00","end":"19:00"},"friday":{"start":"","end":""},"saturday":{"start":"18:00","end":"19:00"},"sunday":{"start":"","end":""}}',
            ]);

            $memberGroup->create([
                'name'     => 'R3',
                'location' => 'DIF',
                'training' => '{"monday":{"start":"","end":""},"tuesday":{"start":"","end":""},"wednesday":{"start":"18:00","end":"19:00"},"thursday":{"start":"","end":""},"friday":{"start":"18:00","end":"19:00"},"saturday":{"start":"","end":""},"sunday":{"start":"","end":""}}',
            ]);

            $memberGroup->create([
                'name'     => 'R4',
                'location' => 'DIF',
                'training' => null,
            ]);

            $memberGroup->create([
                'name'     => 'R5',
                'location' => 'Karadjordje',
                'training' => null,
            ]);

            $memberGroup->create([
                'name'     => 'R6',
                'location' => 'Karadjordje',
                'training' => null,
            ]);

            $memberGroup->create([
                'name'     => 'R7',
                'location' => 'Novi Beograd',
                'training' => null,
            ]);

            $memberGroup->create([
                'name'     => 'R8',
                'location' => 'Novi Beograd',
                'training' => null,
            ]);
        }

    }
