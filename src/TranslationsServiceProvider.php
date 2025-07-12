<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/laravel-translations
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 */

namespace Juzaweb\Translations;

use Illuminate\Support\ServiceProvider;
use Juzaweb\Translations\Contracts\Translation;
use Juzaweb\Translations\Contracts\TranslationFinder as TranslationFinderContract;
use Juzaweb\Translations\TranslationFinder;

class TranslationsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['config']->set('translation-loader.model', \Juzaweb\Translations\Translation::languageLineModel());

        $this->commands(
            [
                \Juzaweb\Translations\Commands\MakeLanguageCommand::class,
                \Juzaweb\Translations\Commands\ExportTranslationCommand::class,
                \Juzaweb\Translations\Commands\ImportTranslationCommand::class,
            ]
        );

        $this->app[Translation::class]->register(
            "translations_package",
            [
                'type' => 'package',
                'key' => 'translations_package',
                'namespace' => 'translation',
                'lang_path' => __DIR__ . '/resources/lang',
                'src_path' => __DIR__,
                'publish_path' => resource_path('lang/vendor/translation'),
            ]
        );

        $this->app->singleton('translatable.locales', Locales::class);
        $this->app->singleton(\Astrotomic\Translatable\Locales::class, Locales::class);
    }

    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'translation');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'translation');

        $this->app->singleton(
            Translation::class,
            function ($app) {
                return new TranslationRepository();
            }
        );

        $this->app->singleton(
            TranslationFinderContract::class,
            function ($app) {
                return new TranslationFinder(
                    $app[Translation::class]
                );
            }
        );

        $this->publishes(
            [
                __DIR__ . '/../../resources/lang' => resource_path('lang/vendor/translation'),
            ],
            'translations_lang'
        );
    }
}
