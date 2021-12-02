<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hwa:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hwaravel Install';

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
    public function handle()
    {
        $this->info('Info: Starting hwaravel installation...');
        $this->info('');

        // running `php artisan migrate`
        if ($this->confirm("Step: Do you want to migrate all tables into database?", false)) {
            $this->call('migrate:fresh');
        }

        $this->info('');
        $this->info("Step: Import sample data...");
        $this->call('db:seed');

        $this->info('');
        $this->info('Success: Install hwaravel successfully!');
    }
}
