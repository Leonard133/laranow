<?php

namespace Leonard133\Laranow;

use Illuminate\Console\Command;

class PackageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laranow:packages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add most needed packages';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Publish laravel debugbar configuration
        copy(base_path('vendor/barryvdh/laravel-debugbar/config/') . 'debugbar.php', config_path('debugbar.php'));
        $this->info('Added laravel debugbar configuration');
    }
}
