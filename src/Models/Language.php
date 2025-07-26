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

class Language extends Model
{
    protected $keyType = 'string';

    protected $primaryKey = 'code';

    protected $table = 'languages';

    protected $fillable = [
        'code',
        'name',
    ];

    public static function existsCode(string $code): bool
    {
        return self::whereCode($code)->exists();
    }
}
