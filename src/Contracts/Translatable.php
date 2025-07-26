<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/laravel-translations
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Translations\Contracts;

interface Translatable extends \Astrotomic\Translatable\Contracts\Translatable
{
    public function getTranslatedFields(): array;
}
