<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://juzaweb.com
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
