<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/laravel-translations
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Translations\Contracts;

interface Translater
{
    public function driver(string $driver): static;

    public function translate(string $text, string $source, string $target, bool $isHTML = false): string;

    public function withProxy(string|array $proxy): static;
}
