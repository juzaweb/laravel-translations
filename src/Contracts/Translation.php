<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/laravel-translations
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Translations\Contracts;

use Illuminate\Support\Collection;
use Juzaweb\Translations\Models\Translation as TranslationModel;

interface Translation
{
    public function modules(): Collection;

    public function find(string $module): array;

    public function register(string $module, array $options = []): void;

    public function importTranslationLine(array $data, bool $force = false): TranslationModel;
}
