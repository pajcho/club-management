<?php

    use Illuminate\Database\Seeder;

    class DatabaseSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {
            // Determine if we want to seed production server or not
            $seedProduction = false;

            if ($seedProduction or app()->environment() == 'production') {
                // This has to be like this to display nicely in console :)
                $message = '' . PHP_EOL;
                $message .= '' . PHP_EOL;
                $message .= '-------------------------------------' . PHP_EOL;
                $message .= '' . PHP_EOL;
                $message .= '    INFO: We dont want to seed production server' . PHP_EOL;
                $message .= '' . PHP_EOL;
                $message .= '    Database was not seeded.' . PHP_EOL;
                $message .= '' . PHP_EOL;
                $message .= '-------------------------------------' . PHP_EOL;
                $message .= '' . PHP_EOL;
                $message .= '' . PHP_EOL;
                exit($message);
            }

            Eloquent::unguard();

            $this->call('MembersSeeder');
            $this->call('MemberGroupsSeeder');
            $this->call('SettingsSeeder');
            $this->call('UsersSeeder');
        }
    }
