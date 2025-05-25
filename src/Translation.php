<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
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
