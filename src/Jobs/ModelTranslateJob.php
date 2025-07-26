<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Translations\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Juzaweb\Translations\Contracts\Translatable;

class ModelTranslateJob implements ShouldQueue
{
    use Queueable, Dispatchable;

    public function __construct(
        protected Translatable $model,
        protected string $sourceLocale,
        protected string $targetLocale
    ) {
    }

    public function handle(): void
    {
        $this->model->translateTo($this->targetLocale, $this->sourceLocale);
    }
}
