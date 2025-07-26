<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/laravel-translations
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Translations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Juzaweb\QueryCache\QueryCacheable;

class Language extends Model
{
    use QueryCacheable;

    protected $keyType = 'string';

    protected $primaryKey = 'code';

    protected $table = 'languages';

    protected $fillable = [
        'code',
        'name',
    ];

    public static function languages(): Collection
    {
        return self::cacheFor(config('core.query_cache.lifetime'))
            ->get()
            ->map(function ($item) {
                $item->regional = config("locales.{$item->code}.regional");
                $item->country = explode('_', strtolower($item->regional))[1] ?? null;
                return $item;
            })
            ->keyBy('code');
    }

    public static function existsCode(string $code): bool
    {
        return self::whereCode($code)->exists();
    }
}
