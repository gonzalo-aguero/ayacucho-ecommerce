<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DeployCommand extends Command
{
    protected $signature = 'app:deploy';
    protected $description = 'Run deploy tasks (migrate and seed, cache clear, links, etc.)';

    public function handle()
    {
        $this->info('Running migrations...');
        Artisan::call('migrate', ['--force' => true]);

        $this->info('Clearing caches...');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:cache');

        $this->info('Creating storage link...');
        Artisan::call('storage:link');

        $this->info('Deployment finished!');
    }
}
