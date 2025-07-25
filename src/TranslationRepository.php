<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/laravel-translations
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Translations;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Juzaweb\Translations\Contracts\Translation as TranslationContract;
use Juzaweb\Translations\Models\Translation;
use RuntimeException;

class TranslationRepository implements TranslationContract
{
    protected array $modules = [];

    /**
     * Register a module to the translation service.
     *
     * @param string $module
     * @param array $options
     * @return void
     */
    public function register(string $module, array $options = []): void
    {
        $this->modules[$module] = $options;
    }

    /**
     * Retrieve the locale repository for a given module.
     *
     * @param string $module The name of the module to retrieve the locale for.
     * @return LocaleRepository The locale repository associated with the specified module.
     * @throws RuntimeException If the module is not found.
     */
    public function locale(string $module): LocaleRepository
    {
        $module = $this->find($module);

        return $this->createTranslationLocale(collect($module));
    }

    /**
     * Find a module by name.
     *
     * @param string $module
     * @return array
     * @throws RuntimeException
     */
    public function find(string $module): array
    {
        if ($module = Arr::get($this->modules, $module)) {
            return $module;
        }

        throw new RuntimeException('Module not found');
    }

    /**
     * Get all registered modules.
     *
     * @return Collection
     */
    public function modules(): Collection
    {
        return collect($this->modules);
    }

    /**
     * Import a translation line into the system.
     *
     * @param array $data The translation data, including keys such as locale, group, namespace, key, object_type, object_key, and value.
     * @param bool $force Whether to forcefully update an existing translation line if it exists.
     * @return Translation The imported or updated translation model.
     */
    public function importTranslationLine(array $data, bool $force = false): Translation
    {
        if ($force) {
            return Translation::updateOrCreate(
                Arr::only($data, ['locale', 'group', 'namespace', 'key', 'object_type', 'object_key']),
                Arr::only($data, ['value'])
            );
        }

        return Translation::firstOrCreate(
            Arr::only($data, ['locale', 'group', 'namespace', 'key', 'object_type', 'object_key']),
            Arr::only($data, ['value'])
        );
    }

    protected function createTranslationLocale(Collection $module): LocaleRepository
    {
        return new LocaleRepository($module);
    }
}
