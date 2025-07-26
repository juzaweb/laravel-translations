<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/laravel-translations
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Translations\Contracts;

use Astrotomic\Translatable\Contracts\Translatable as BaseTranslatable;

interface Translatable extends BaseTranslatable
{
    /**
     * Get the translated fields of the model.
     *
     * @return array
     */
    public function getTranslatedFields(): array;

    /**
     * Translate the model to a specific locale.
     *
     * @param  string  $locale
     * @param  string  $source
     * @param  array  $options
     * @return bool
     */
    public function translateTo(string $locale, string $source = 'en', array $options = []): bool;
}
