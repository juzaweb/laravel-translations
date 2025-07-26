<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/laravel-translations
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Translations\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Juzaweb\Translations\Enums\TranslateHistoryStatus;
use Juzaweb\Translations\Exceptions\TranslationExistException;
use Juzaweb\Translations\Models\TranslateHistory;
use Juzaweb\Translations\Translater;

/**
 * @property array $translatedAttributes
 * @property array $translatedAttributeFormats
 * @method static Builder|static withTranslation(?string $locale = null, ?array $with = null)
 * @method static Builder|static withTranslationAndMedia(?string $locale = null, ?array $with = null)
 * @method static Builder|static translatedIn(?string $locale = null)
 * @method static Builder|static whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 */
trait Translatable
{
    use \Astrotomic\Translatable\Translatable;

    public function translateHistories(): MorphMany
    {
        return $this->morphMany(TranslateHistory::class, 'translateable');
    }

    /**
     * @param  string  $locale
     * @return null|TranslateHistory
     */
    public function getTranslateHistory(string $locale): ?TranslateHistory
    {
        return $this->translateHistories()->where('locale', $locale)->first();
    }

    public function scopeWithTranslation(Builder $query, ?string $locale = null, ?array $with = null): Builder
    {
        $locale = $locale ?: $this->locale();

        return $query->with([
            'translations' => function (Relation $query) use ($locale, $with) {
                if ($this->useFallback()) {
                    $countryFallbackLocale = $this->getFallbackLocale($locale); // e.g. de-DE => de
                    $locales = array_unique([$locale, $countryFallbackLocale, $this->getFallbackLocale()]);

                    return $query
                        ->when($with, fn ($q) => $q->with($with))
                        ->whereIn($this->getTranslationsTable().'.'.$this->getLocaleKey(), $locales);
                }

                return $query
                    ->when($with, fn ($q) => $q->with($with))
                    ->where($this->getTranslationsTable().'.'.$this->getLocaleKey(), $locale);
            },
        ]);
    }

    public function getTranslatedFields(): array
    {
        return $this->translatedAttributes ?? [];
    }

    public function translateTo(string $locale, string $source = 'en', array $options = []): bool
    {
        $options = array_merge(
            [
                'force' => false,
            ],
            $options
        );

        if (!$options['force'] && $this->hasTranslation($locale)) {
            throw TranslationExistException::make($locale);
        }

        $translation = $this->translate($source);
        $translateHistory = $this->getTranslateHistory($locale);

        if ($translation === null) {
            throw \Juzaweb\Translations\Exceptions\TranslationDoesNotExistException::make($source);
        }

        try {
            $translated = [];
            foreach ($this->translatedAttributes as $translatedAttribute) {
                if (! isset($translation->{$translatedAttribute})) {
                    $translated[$translatedAttribute] = null;
                    continue;
                }

                if ($translatedAttribute === 'slug'
                    || Arr::get($this->translatedAttributeFormats, $translatedAttribute) == 'slug'
                ) {
                    $translated[$translatedAttribute] = null;
                    continue;
                }

                $translated[$translatedAttribute] = app(Translater::class)->translate(
                    $translation->{$translatedAttribute},
                    $source,
                    $locale,
                    isset($this->autoTranslateFormats[$translatedAttribute])
                    && $this->autoTranslateFormats[$translatedAttribute] === 'html'
                );
            }
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            $translateHistory?->update([
                'status' => TranslateHistoryStatus::FAILED,
                'error' => get_error_by_exception($e),
            ]);

            return false;
        } catch (\Throwable $e) {
            $translateHistory?->update([
                'status' => TranslateHistoryStatus::FAILED,
                'error' => get_error_by_exception($e),
            ]);

            throw $e;
        }

        return DB::transaction(
            function () use ($locale, $translated, $translation, $translateHistory) {
                $translateHistory?->update(['status' => TranslateHistoryStatus::SUCCESS]);

                if ($newTranslation = $this->translate($locale)) {
                    $translated = array_filter($translated);
                    unset($translated['locale'], $translated['slug']);
                    return $newTranslation->update($translated);
                }

                $newTranslation = $translation->replicate();
                $newTranslation->fill($translated);
                $newTranslation->locale = $locale;
                return $newTranslation->save();
            }
        );
    }
}
