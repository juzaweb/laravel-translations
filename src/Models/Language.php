<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/laravel-translations
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Translations\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $keyType = 'string';

    protected $primaryKey = 'code';

    protected $table = 'languages';

    protected $fillable = [
        'code',
        'name',
        'default',
    ];

    protected $casts = [
        'default' => 'bool',
    ];

    protected array $filterable = ['code', 'name'];

    protected array $searchable = ['code', 'name'];

    protected array $sortable = ['code', 'name'];

    protected array $sortDefault = ['code' => 'asc'];

    public static function existsCode(string $code): bool
    {
        return self::whereCode($code)->exists();
    }

    public static function languages(): Collection
    {
        return self::cacheFor(config('core.query_cache.lifetime'))
            ->get()
            ->keyBy('code');
    }

    public function scopeIsDefault(Builder $query): Builder
    {
        return $query->where(['default' => true]);
    }
}
