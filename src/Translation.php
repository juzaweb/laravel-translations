<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/laravel-translations
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Translations;

class Translation
{
    protected static string $languageLineModel = Models\LanguageLine::class;

    public static function languageLineModel(): string
    {
        return static::$languageLineModel;
    }

    public static function useLanguageLineModel(string $model): void
    {
        static::$languageLineModel = $model;
    }
}
