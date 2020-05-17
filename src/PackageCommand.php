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
    protected $signature = 'add:packages
                            {--X|exclude=}
                            {--I|include=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add open source packages without going through documentation to configure';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $exclude = $this->option('exclude');
        $include = $this->option('include');
        $excluded = (\Str::contains($exclude, ',')) ? explode(',', $exclude) : [$exclude];
        $included = (\Str::contains($include, ',')) ? explode(',', $include) : [$include];

        // Setup laravel debugbar configuration
        if (!file_exists(config_path('debugbar.php')) && !in_array('debugbar', $excluded)) {
            $this->info('Installing debugbar...');
            exec('composer require barryvdh/laravel-debugbar --quiet');
            $this->info('Configuring debugbar...');
            copy(base_path('vendor/barryvdh/laravel-debugbar/config/debugbar.php'), config_path('debugbar.php'));
            file_put_contents(config_path('debugbar.php'), str_replace("false, // Display Laravel authentication status", "true, // Display Laravel authentication status", file_get_contents(config_path('debugbar.php'))));
            $this->info('Done setup debugbar');
        }

        // Setup telescope
        if (!file_exists(config_path('telescope.php')) && !in_array('telescope', $excluded)) {
            $this->info('Installing telescope...');
            exec('composer require laravel/telescope --quiet');
            $this->info('Configuring telescope...');
            copy(base_path('vendor/laravel/telescope/config/telescope.php'), config_path('telescope.php'));
            \File::copyDirectory(base_path('vendor/laravel/telescope/public'), public_path('vendor/telescope'));
            copy(base_path('vendor/laravel/telescope/stubs/TelescopeServiceProvider.stub'), app_path('Providers/TelescopeServiceProvider.php'));
            $this->registerTelescopeServiceProvider();
            $this->info('Done setup telescope');
        }

        // Setup permission
        if (!file_exists(config_path('permission.php')) && !in_array('permission', $excluded)) {
            $this->info('Installing permission...');
            exec('composer require spatie/laravel-permission --quiet');
            $this->info('Configuring permission...');
            copy(base_path('vendor/spatie/laravel-permission/config/permission.php'), config_path('permission.php'));
            copy(base_path('vendor/spatie/laravel-permission/database/migrations/create_permission_tables.php.stub'), database_path('migrations/' . date('Y_m_d_His') . '_create_permission_tables.php'));
            $this->info('Done setup spatie permission');
        }

        // Setup core datable
        if (!file_exists(config_path('datatables.php')) && !in_array('datatable', $excluded)) {
            $this->info('Installing datatable...');
            exec('composer require yajra/laravel-datatables-oracle --quiet');
            $this->info('Configuring datatable...');
            copy(base_path('vendor/yajra/laravel-datatables/config/datatables.php'), config_path('datatables.php'));
            $this->info('Done setup datatable');
        }

        // Setup html datable
        if (!file_exists(config_path('datatables.php')) && !in_array('datatable-html', $excluded)) {
            $this->info('Installing datatable html...');
            exec('composer require yajra/laravel-datatables-html --quiet');
            $this->info('Configuring datatable html...');
            copy(base_path('vendor/yajra/laravel-datatables-html/resources/config/config.php'), config_path('datatables-html.php'));
            \File::copyDirectory(base_path('vendor/yajra/laravel-datatables-html/resources/views'), base_path('/resources/views/vendor/datatables'));
            $this->info('Done setup datatable html');
        }

        // Setup button datable
        if (!file_exists(config_path('datatables.php')) && !in_array('datatable-button', $excluded)) {
            $this->info('Installing datatable button...');
            exec('composer require yajra/laravel-datatables-buttons --quiet');
            $this->info('Configuring datatable button...');
            copy(base_path('vendor/yajra/laravel-datatables-buttons/config/config.php'), config_path('datatables-buttons.php'));
            copy(base_path('vendor/yajra/laravel-datatables-buttons/resources/assets/buttons.server-side.js'), public_path('vendor/datatables/buttons.server-side.js'));
            \File::copyDirectory(base_path('vendor/yajra/laravel-datatables-buttons/resources/views'), base_path('/resources/views/vendor/datatables'));
            $this->info('Done setup datatable button');
        }
1
        // Setup datatable
        exec('composer dump-autoload -o --quiet');
        $this->info('Every packages setup done.');
    }

    protected function registerTelescopeServiceProvider()
    {
        $namespace = \Str::replaceLast('\\', '', $this->laravel->getNamespace());

        $appConfig = file_get_contents(config_path('app.php'));

        if (\Str::contains($appConfig, $namespace . '\\Providers\\TelescopeServiceProvider::class')) {
            return;
        }

        $lineEndingCount = [
            "\r\n" => substr_count($appConfig, "\r\n"),
            "\r" => substr_count($appConfig, "\r"),
            "\n" => substr_count($appConfig, "\n"),
        ];

        $eol = array_keys($lineEndingCount, max($lineEndingCount))[0];

        file_put_contents(config_path('app.php'), str_replace(
            "{$namespace}\\Providers\RouteServiceProvider::class," . $eol,
            "{$namespace}\\Providers\RouteServiceProvider::class," . $eol . "        {$namespace}\Providers\TelescopeServiceProvider::class," . $eol,
            $appConfig
        ));

        file_put_contents(app_path('Providers/TelescopeServiceProvider.php'), str_replace(
            "namespace App\Providers;",
            "namespace {$namespace}\Providers;",
            file_get_contents(app_path('Providers/TelescopeServiceProvider.php'))
        ));
    }
}
