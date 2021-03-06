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

        $totalPackages = (6 * 3) - (count($excluded) * 3) + (count($included) * 3);
        $bar = $this->output->createProgressBar($totalPackages);
        $bar->setFormat("%message% \n%current%/%max%[%bar%] - %percent:3s%%");

        $this->setupDebugbar($bar, $excluded);

        $this->setupTelescope($bar, $excluded);

        $this->setupPermission($bar, $excluded);

        $this->setupDatatable($bar, $excluded);

        $this->setupDatatableHtml($bar, $excluded);

        $this->setupDatatableButton($bar, $excluded);

        // Finishing Setup
        exec('composer dump-autoload -o --quiet');
        $bar->setMessage('All packages complete installed. Happy Coding~');
        $bar->finish();
        $this->callSilent('cache:clear');
        $this->callSilent('config:clear');
        $this->callSilent('route:clear');
        $this->callSilent('ui:auth');
        $this->output->newLine();
        sleep(1);
    }

    protected function setupDebugbar($bar, $excluded)
    {
        // Setup laravel debugbar configuration
        if (!file_exists(config_path('debugbar.php')) && !in_array('debugbar', $excluded)) {
            $bar->setMessage('Installing debugbar...', 'message');
            exec('composer require barryvdh/laravel-debugbar --quiet');
            $bar->advance();
            sleep(1);
            $bar->setMessage('Configuring debugbar...', 'message');
            copy(base_path('vendor/barryvdh/laravel-debugbar/config/debugbar.php'), config_path('debugbar.php'));
            file_put_contents(config_path('debugbar.php'), str_replace("false, // Display Laravel authentication status", "true, // Display Laravel authentication status", file_get_contents(config_path('debugbar.php'))));
            $bar->advance();
            sleep(1);
            $bar->setMessage('Done debugbar setup');
            $bar->advance();
            sleep(1);
        } elseif (file_exists(config_path('debugbar.php')) && !in_array('debugbar', $excluded)) {
            $bar->setMessage('Skip debugbar setup');
            $bar->advance(3);
            sleep(1);
        }
    }

    protected function setupTelescope($bar, $excluded)
    {
        // Setup telescope
        if (!file_exists(config_path('telescope.php')) && !in_array('telescope', $excluded)) {
            $bar->setMessage('Installing telescope...');
            exec('composer require laravel/telescope --quiet');
            $bar->advance();
            sleep(1);
            $bar->setMessage('Configuring telescope...');
            copy(base_path('vendor/laravel/telescope/config/telescope.php'), config_path('telescope.php'));
            \File::copyDirectory(base_path('vendor/laravel/telescope/public'), public_path('vendor/telescope'));
            copy(base_path('vendor/laravel/telescope/stubs/TelescopeServiceProvider.stub'), app_path('Providers/TelescopeServiceProvider.php'));
            $this->registerTelescopeServiceProvider();
            $bar->advance();
            sleep(1);
            $bar->setMessage('Done telescope setup');
            $bar->advance();
            sleep(1);
        } elseif (file_exists(config_path('telescope.php')) && !in_array('telescope', $excluded)) {
            $bar->setMessage('Skip telescope setup');
            $bar->advance(3);
            sleep(1);
        }
    }

    protected function setupPermission($bar, $excluded)
    {
        // Setup permission
        if (!file_exists(config_path('permission.php')) && !in_array('permission', $excluded)) {
            $bar->setMessage('Installing permission...');
            exec('composer require spatie/laravel-permission --quiet');
            $bar->advance();
            sleep(1);
            $bar->setMessage('Configuring permission...');
            copy(base_path('vendor/spatie/laravel-permission/config/permission.php'), config_path('permission.php'));
            copy(base_path('vendor/spatie/laravel-permission/database/migrations/create_permission_tables.php.stub'), database_path('migrations/' . date('Y_m_d') . '_000000_create_permission_tables.php'));
            $bar->advance();
            sleep(1);
            $bar->setMessage('Done spatie permission setup');
            $bar->advance();
            sleep(1);
        } elseif (file_exists(config_path('permission.php')) && !in_array('permission', $excluded)) {
            $bar->setMessage('Skip permission setup');
            $bar->advance(3);
            sleep(1);
        }
    }

    protected function setupDatatable($bar, $excluded)
    {
        // Setup core datable
        if (!file_exists(config_path('datatables.php')) && !in_array('datatable', $excluded)) {
            $bar->setMessage('Installing datatable...');
            exec('composer require yajra/laravel-datatables-oracle --quiet');
            $bar->advance();
            sleep(1);
            $bar->setMessage('Configuring datatable...');
            copy(base_path('vendor/yajra/laravel-datatables-oracle/src/config/datatables.php'), config_path('datatables.php'));
            $bar->advance();
            sleep(1);
            $bar->setMessage('Done datatable setup');
            $bar->advance();
            sleep(1);
        } elseif (file_exists(config_path('datatables.php')) && !in_array('datatable', $excluded)) {
            $bar->setMessage('Skip datatable setup');
            $bar->advance(3);
            sleep(1);
        }
    }

    protected function setupDatatableHtml($bar, $excluded)
    {
        // Setup html datable
        if (!file_exists(config_path('datatables-html.php')) && !in_array('datatable-html', $excluded)) {
            $bar->setMessage('Installing datatable html...');
            exec('composer require yajra/laravel-datatables-html --quiet');
            $bar->advance();
            sleep(1);
            $bar->setMessage('Configuring datatable html...');
            copy(base_path('vendor/yajra/laravel-datatables-html/src/resources/config/config.php'), config_path('datatables-html.php'));
            \File::copyDirectory(base_path('vendor/yajra/laravel-datatables-html/src/resources/views'), base_path('/resources/views/vendor/datatables'));
            $bar->advance();
            sleep(1);
            $bar->setMessage('Done datatable html setup');
            $bar->advance();
            sleep(1);
        } elseif (file_exists(config_path('datatables-html.php')) && !in_array('datatable-html', $excluded)) {
            $bar->setMessage('Skip datatable html setup');
            $bar->advance(3);
            sleep(1);
        }
    }

    protected function setupDatatableButton($bar, $excluded)
    {
        // Setup button datable
        if (!file_exists(config_path('datatables-buttons.php')) && !in_array('datatable-button', $excluded)) {
            $bar->setMessage('Installing datatable button...');
            exec('composer require yajra/laravel-datatables-buttons --quiet');
            $bar->advance();
            sleep(1);
            $bar->setMessage('Configuring datatable button...');
            copy(base_path('vendor/yajra/laravel-datatables-buttons/src/config/config.php'), config_path('datatables-buttons.php'));
            \File::copyDirectory(base_path('vendor/yajra/laravel-datatables-buttons/src/resources/assets'), public_path('vendor/datatables'));
            \File::copyDirectory(base_path('vendor/yajra/laravel-datatables-buttons/src/resources/views'), base_path('/resources/views/vendor/datatables'));
            $bar->advance();
            sleep(1);
            $bar->setMessage('Done datatable button setup');
            $bar->advance();
            sleep(1);
        } elseif (file_exists(config_path('datatables-buttons.php')) && !in_array('datatable-button', $excluded)) {
            $bar->setMessage('Skipping datatable button setup');
            $bar->advance(3);
            sleep(1);
        }
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
