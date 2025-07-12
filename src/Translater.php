<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 */

namespace Juzaweb\Translations;

use Juzaweb\Translations\Contracts\Translater as TranslaterContract;

class Translater implements TranslaterContract
{
    protected ?string $driver = null;

    protected string|array|null $proxy = null;

    public function driver(string $driver): static
    {
        $this->driver = $driver;

        return $this;
    }

    public function translate(string $text, string $source, string $target, bool $isHTML = false): string
    {
        throw_if(empty($text), new \InvalidArgumentException('Text is empty'));

        $driver = $this->driver ?? config('premium.translaters.default');

        return app(config("premium.translaters.drivers.{$driver}.translater"))
            ->translate($text, $source, $target, $isHTML);
    }

    public function withProxy(string|array $proxy): static
    {
        $this->proxy = $proxy;

        return $this;
    }
}
