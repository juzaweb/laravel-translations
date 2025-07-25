<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/laravel-translations
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Translations\Translators;

use Illuminate\Support\Facades\Http;
use Juzaweb\Translations\Contracts\Translator as TranslatorContract;

class GoogleTranslator implements TranslatorContract
{
    protected string|array|null $proxy = null;

    public function translate(string $text, string $source, string $target, bool $isHTML = false): ?string
    {
        $apiKey = config('laravel-translation.translator.drivers.google.key');

        $response = Http::throw()->post(
            'https://translation.googleapis.com/language/translate/v2',
            [
                'q' => $text,
                'source' => $source,
                'target' => $target,
                'format' => $isHTML ? 'html' : 'text',
                'key' => $apiKey,
                'proxy' => $this->proxy,
            ]
        );

        if ($response->successful()) {
            return $response['data']['translations'][0]['translatedText'] ?? null;
        }

        return null;
    }

    public function withProxy(string|array $proxy): static
    {
        $this->proxy = $proxy;

        return $this;
    }
}
