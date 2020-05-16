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
            $this->info('Done setup debugbar');
        }

        // Setup telescope
        if (!file_exists(config_path('telescope.php')) && !in_array('telescope', $excluded)) {
            \Artisan::call('telescope:install');
            \Artisan::call('telescope:publish');
            $this->info('Done setup telescope');
        }

        // Setup permission
        if (!file_exists(config_path('permission.php')) && !in_array('permission', $excluded)) {
            $timestamp = date('Y_m_d_His');
            copy(base_path('vendor/spatie/laravel-permission/config/permission.php'), config_path('permission.php'));
            copy(base_path('vendor/spatie/laravel-permission/database/migrations/create_permission_tables.php.stub'), database_path('migrations/' . $timestamp . '_create_permission_tables.php'));
            $this->info('Done setup spatie permission');
        }

        $this->info('Every packages setup done.');
    }
}
