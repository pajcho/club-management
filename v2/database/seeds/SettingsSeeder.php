<?php

    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\DB;

    class SettingsSeeder extends Seeder
    {
        public function run()
        {
            // Delete all users
            DB::table('settings')->truncate();

            $settings = app()->make('App\Modules\Settings\Repositories\SettingsRepositoryInterface');

            $settings->create([
                'title'       => 'Site Title',
                'key'         => 'site_title',
                'value'       => 'GK DIF',
                'description' => '* This is used as site title inside browser',
            ]);

            $settings->create([
                'title'       => 'Club Name',
                'key'         => 'club_name',
                'value'       => 'Gimnasticki klub "DIF" - Beograd',
                'description' => '* This will be used when generating documents',
            ]);

            $settings->create([
                'title'       => 'Club Address',
                'key'         => 'club_address',
                'value'       => 'Blagoja Parovica 144',
                'description' => '* This will be used when generating documents',
            ]);

            $settings->create([
                'title'       => 'Number of fields per row on settings page',
                'key'         => 'per_row',
                'value'       => 2,
                'description' => '* This will change layout on this page',
            ]);

            $settings->create([
                'title'       => 'Month in year that marks start of season',
                'key'         => 'season_starts',
                'value'       => 9,
                'description' => '* This will be used when generating documents',
            ]);

            $settings->create([
                'title'       => 'Month in year that marks end of season',
                'key'         => 'season_ends',
                'value'       => 6,
                'description' => '* This will be used when generating documents',
            ]);
        }

    }
