<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

return [

    'translator' => [

        'driver' => env('TRANSLATOR_DRIVER', 'google'),

        'drivers' => [
            'google' => [
                'class' => \Juzaweb\Translations\Translators\GoogleTranslator::class,
                'key' => env('GOOGLE_TRANSLATE_API_KEY'),
            ],
        ],
    ],

];
 