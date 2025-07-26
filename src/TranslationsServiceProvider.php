<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/laravel-translations
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Translations;

use Illuminate\Support\ServiceProvider;
use Juzaweb\Translations\Commands\ConvertConfigCountryCommand;
use Juzaweb\Translations\Commands\ExportTranslationCommand;
use Juzaweb\Translations\Commands\ImportTranslationCommand;
use Juzaweb\Translations\Commands\MakeLanguageCommand;
use Juzaweb\Translations\Commands\ModelTranslateCommand;
use Juzaweb\Translations\Commands\TranslateCommand;
use Juzaweb\Translations\Contracts\Translation;
use Juzaweb\Translations\Contracts\TranslationFinder as TranslationFinderContract;
use Juzaweb\Translations\Contracts\Translator;

class TranslationsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['config']->set('translation-loader.model', \Juzaweb\Translations\Translation::languageLineModel());

        $this->commands(
            [
                MakeLanguageCommand::class,
                ExportTranslationCommand::class,
                ImportTranslationCommand::class,
                TranslateCommand::class,
                ModelTranslateCommand::class,
                ConvertConfigCountryCommand::class,
            ]
        );

        // $this->app[Translation::class]->register(
        //     "laravel",
        //     [
        //         'type' => 'laravel',
        //         'key' => 'laravel',
        //         'namespace' => 'laravel',
        //         'lang_path' => resource_path('lang'),
        //         'src_path' => app_path(),
        //         'publish_path' => resource_path('lang'),
        //     ]
        // );

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
        $this->mergeConfigFrom(
            __DIR__ . '/../config/locales.php',
            'locales'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../config/laravel-translation.php',
            'laravel-translation'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../config/countries.php',
            'countries'
        );

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

        $this->app->bind(
            Translator::class,
            function ($app) {
                $driver = $app['config']->get('laravel-translation.translator.driver', 'google');

                return new ($app['config']->get("laravel-translation.translator.drivers.{$driver}")['class']);
            }
        );

        $this->app->singleton(
            Contracts\IP2Location::class,
            function ($app) {
                $dataPath = __DIR__ . '/../database/iplocation/IPV6-COUNTRY.BIN';
                return new IP2LocationFactory($dataPath);
            }
        );

        $this->publishes(
            [
                __DIR__ . '/../../resources/lang' => resource_path('lang/vendor/translation'),
            ],
            'translations_lang'
        );

        $this->publishes(
            [
                __DIR__ . '/../config/laravel-translation.php' => config_path('laravel-translation.php'),
                __DIR__ . '/../config/locales.php' => config_path('locales.php'),
                __DIR__ . '/../config/countries.php' => config_path('countries.php'),
            ],
            'translations_config'
        );
    }
}
