<?php

namespace Leonard133\Laranow;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

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
        Artisan::call('vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"');
    }
}
