<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Translations\Commands;

use Illuminate\Console\Command;
use Juzaweb\Translations\Models\Language;
use Symfony\Component\Console\Input\InputOption;

class ModelTranslateCommand extends Command
{
    protected $name = 'translation:model';

    public function handle(): int
    {
        $source = $this->option('source') ?? config('translatable.fallback_locale');
        if ($this->option('target')) {
            $targets = explode(',', $this->option('target'));
        } else {
            $targets = Language::languages()->keys()->filter(
                fn ($locale) => $locale !== config('translatable.fallback_locale')
            )->toArray();
        }

        $model = $this->argument('model');

        if (!class_exists($model)) {
            $this->error("Model {$model} does not exist.");
            return self::FAILURE;
        }

        foreach ($targets as $target) {
            $model::notTranslatedIn($target)->chunkById(
                100,
                function ($pages) use ($target, $source) {
                    foreach ($pages as $page) {
                        $newPage = $page->translateTo($target);

                        $this->info("Translated page ID {$page->id} from {$source} to {$target}");
                    }
                }
            );
        }

        return self::SUCCESS;
    }

    protected function getArguments(): array
    {
        return [
            ['model', InputOption::VALUE_REQUIRED, 'The model to translate'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['source', 's', InputOption::VALUE_OPTIONAL, 'The source language to translate from', null],
            ['target', 't', InputOption::VALUE_OPTIONAL, 'The target languages to translate to, separated by commas', null],
        ];
    }
}
