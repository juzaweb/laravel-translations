<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 */

namespace Juzaweb\Translations\Contracts;

interface Translater
{
    public function driver(string $driver): static;

    public function translate(string $text, string $source, string $target, bool $isHTML = false): string;

    public function withProxy(string|array $proxy): static;
}
