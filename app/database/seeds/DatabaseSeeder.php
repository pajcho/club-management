<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Determine if we want to seed production server or not
        $seedProduction = false;

        if($seedProduction or App::environment() == 'production')
        {
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

        $this->checkAndCall('MembersSeeder');
        $this->checkAndCall('MemberGroupsSeeder');
        $this->checkAndCall('SettingsSeeder');
        $this->checkAndCall('UsersSeeder');
    }

    /**
     * Check if environment specific seeder exists
     * and run that instead of standard seeder
     *
     * @param $seeder
     */
    public function checkAndCall($seeder)
    {
        $environment = ucfirst(App::environment());
        $seederFile = $seeder . '.php';

        if(File::exists(app_path() . '/database/seeds/' . $environment . $seederFile))
        {
            $this->call($environment . $seeder);
        }
        else
        {
            $this->call($seeder);
        }

    }

}
