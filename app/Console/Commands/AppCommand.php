<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class AppCommand extends Command {

    /**
     * The console command name.
     *
     * @var	string
     */
    protected $name = 'app:install';

    /**
     * The console command description.
     *
     * @var	string
     */
    protected $description = 'Install application by running migrations and database seeders';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->comment('=====================================');
        $this->comment('');
        $this->info('  Installing...');
        $this->comment('');
        $this->info('    Application is being installed.');
        $this->info('    This can take a few seconds.');
        $this->comment('');
        $this->comment('-------------------------------------');
        $this->comment('');

        // Drop previos database tables to make sure we have all the new data
        $this->dropTables();
        
        // Run the Migrations
        $this->call('migrate');

        // Seed the tables with dummy data
        $this->call('db:seed');
        
        $this->comment('');
        $this->comment('');
        $this->comment('-------------------------------------');
        $this->comment('');
        $this->info('    SUCCESS: Application installed!!');
        $this->comment('');
        $this->comment('-------------------------------------');
        $this->comment('');
        $this->comment('');
    }
    
    /**
     * Drop all current databse tables
     */
    public function dropTables()
    {
        Schema::dropIfExists('migrations');
        Schema::dropIfExists('members');
        Schema::dropIfExists('member_groups');
        Schema::dropIfExists('member_group_data');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('users_groups');
        Schema::dropIfExists('users_groups_data');
        Schema::dropIfExists('history');
        Schema::dropIfExists('date_history');
        Schema::dropIfExists('results');
        Schema::dropIfExists('result_categories');
    }
}
