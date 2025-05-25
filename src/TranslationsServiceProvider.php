<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Translations;

use Illuminate\Support\ServiceProvider;
use Juzaweb\Translations\Contracts\Translation;

class TranslationsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['config']->set('translation-loader.model', \Juzaweb\Translations\Translation::languageLineModel());
    }

    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->app->singleton(
            Translation::class,
            function ($app) {
                return new TranslationRepository();
            }
        );
    }
}
