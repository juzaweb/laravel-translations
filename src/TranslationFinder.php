<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/laravel-translations
 * @author     Larabiz Team <admin@larabiz.com>
 * @link       https://juzaweb.com
 * @license    MIT
 */

namespace Juzaweb\Translations\Contracts;

use Juzaweb\Translations\TranslationExporter;
use Juzaweb\Translations\TranslationImporter;

/**
 * @see \Juzaweb\Translations\TranslationFinder
 */
interface TranslationFinder
{
    public function find(string $path, string $locale = 'en'): array;

    public function export(string $module): TranslationExporter;

    public function import(string $module): TranslationImporter;
}
