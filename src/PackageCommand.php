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
    protected $signature = 'laranow:packages
                            {--X|exclude=}';

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
        $exclude = $this->option('exclude');
        $excluded = (\Str::contains($exclude, ',')) ? explode(',', $exclude) : [$exclude];

        // Setup laravel debugbar configuration
        if (!file_exists(config_path('debugbar.php')) && !in_array('debugbar', $excluded)) {
            copy(base_path('vendor/barryvdh/laravel-debugbar/config/debugbar.php'), config_path('debugbar.php'));
            file_put_contents(config_path('debugbar.php'), str_replace("false, // Display Laravel authentication status", "true, // Display Laravel authentication status", file_get_contents(config_path('debugbar.php'))));
            $this->info('Done laravel debugbar setup');
        }

        // Setup laravel telescope
        if (!file_exists(config_path('telescope.php')) && !in_array('debugbar', $excluded)) {
//            copy(base_path('vendor/laravel/telescope/config/telescope.php'), config_path('telescope.php'));
            \Artisan::call('telescope:install');
            \Artisan::call('telescope:publish');
            $this->info('Done laravel telescope setup');
        }

        $this->info('Every packages setup done.');
    }
}
