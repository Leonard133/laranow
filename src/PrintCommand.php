<?php

namespace Leonard133\Laranow;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;

class PrintCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:blueprint {model* : Please specify at least one model} {--api} {--F | --force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make initial blueprint YAML file';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $model = collect($this->argument('model'))->map(function ($item) {
            return ucfirst($item);
        });
        foreach ($model as $m) {
            $data["model"][$m] = ['content' => 'string'];
            $data["controller"][$m] = ['resource' => 'web'];
            if ($this->option('api'))
                $data["controller"]['API\\' . $m] = ['resource' => 'api'];
        }
        $content = [
            'models' => $data['model'],
            'seeders' => implode(', ', $model->toArray()),
            'controllers' => $data['controller'],
        ];
        $yaml = Yaml::dump($content, 3);
        $comment = "# https://blueprint.laravelshift.com/ \n";
        $yaml = $comment . $yaml;
        if ($this->option('force')) {
            File::put(base_path('draft.yaml'), $yaml);
        } else {
            if (!File::exists(base_path('draft.yaml')))
                File::put(base_path('draft.yaml'), $yaml);
        }
    }
}
