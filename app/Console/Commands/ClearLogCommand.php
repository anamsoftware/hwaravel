<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ClearLogCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hwa:log:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear log file';

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * Create a new command instance.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->files = $filesystem;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->files->isDirectory(storage_path('logs'))) {
            foreach ($this->files->allFiles(storage_path('logs')) as $file) {
                $this->files->delete($file->getPathname());
            }
        }

        $this->info('Success: Clear log files successfully!');
    }
}
