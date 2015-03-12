<?php

    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\DB;

    class UsersSeeder extends Seeder
    {
        public function run()
        {
            // Delete all users
            DB::table('users')->truncate();

            $user = app()->make('App\Modules\Users\Repositories\UserRepositoryInterface');

            // Add default user but you can add any number of users in this format
            $user->create([
                'first_name' => env('APP_ADMIN_FIRSTNAME', 'firstname'),
                'last_name'  => env('APP_ADMIN_LASTNAME', 'lastname'),
                'username'   => env('APP_ADMIN_USERNAME', 'username'),
                'email'      => env('APP_ADMIN_EMAIL', 'email@email.com'),
                'password'   => bcrypt(env('APP_ADMIN_PASSWORD', 'password')),
                'type'       => 'admin', // can be 'admin' or 'trainer'
            ]);
        }

    }
