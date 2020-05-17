<?php

namespace Leonard133\Laranow;

use Illuminate\Console\Command;

class LaranowCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add
                    { type : The preset type (t1, t2, t3) }
                    { --auth : Multiple auth }
                    { --packages : Add needed packages }
                    { --option=* : Pass an option to the preset command }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initial setup to a template and requires needed packages';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (static::hasMacro($this->argument('type'))) {
            return call_user_func(static::$macros[$this->argument('type')], $this);
        }

        if (!in_array($this->argument('type'), ['t1'])) {
            throw new \InvalidArgumentException('Invalid preset.');
        }

        $this->{$this->argument('type')}();

        if ($this->option('auth')) {
            $this->call('laranow:auth');
        }

        if ($this->option('packages')) {
            $this->call('laranow:packages');
        }
    }

    protected function t1()
    {
        Presets\Template1::install();

        $this->info('Template 1 scaffolding installed successfully.');
        $this->comment('Please run "npm install && npm run dev" to compile your fresh scaffolding.');
    }
}
