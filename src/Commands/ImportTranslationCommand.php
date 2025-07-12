<?php

namespace Juzaweb\Translations\Commands;

use Illuminate\Console\Command;
use Juzaweb\Translations\Contracts\Translation;
use Juzaweb\Translations\Contracts\TranslationFinder;
use Symfony\Component\Console\Input\InputOption;

class ImportTranslationCommand extends Command
{
    protected $name = 'translation:import';

    public function handle(): int
    {
        if ($module = $this->option('module')) {
            $modules = [$module => app(Translation::class)->find($module)];
        } else {
            $modules = app(Translation::class)->modules();
        }

        foreach ($modules as $module => $options) {
            $import = app(TranslationFinder::class)->import($module)->run();

            $this->info("Imported {$import} rows from {$module}");
        }

        return static::SUCCESS;
    }

    protected function getOptions(): array
    {
        return [
            ['module', 'm', InputOption::VALUE_OPTIONAL, 'The module to import', null],
        ];
    }
}
