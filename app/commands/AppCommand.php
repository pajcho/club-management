<?php

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
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void
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

        // Generate the Application Encryption key
        $this->call('key:generate');

        // Drop previos database tables to make sure we have all the new data
        $this->dropTables();
        
        // Run the Revisionable Migrations
//        $this->call('migrate', array('--package' => 'venturecraft/revisionable'));

        // Run the Migrations
        $this->call('migrate');

        // Seed the tables with dummy data
        $this->call('db:seed');
        
        // Set writable permissions to required folders
//        $this->setFolders();
        
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
        Schema::dropIfExists('date_history');
        Schema::dropIfExists('users_groups');
        Schema::dropIfExists('history');
        Schema::dropIfExists('results');
        Schema::dropIfExists('result_categories');
    }
    
    /**
     * Set writable permission to folders
     */
    public function setFolders()
    {
        $this->comment('');
        $this->comment('');
        $this->comment('-------------------------------------');
        $this->comment('');
        $this->info('    INFO: You might need to set folder permissions by running');
        $this->info('          "chmod 777 -R public/app"');
        $this->info('          with super user permissions');
    }
}
