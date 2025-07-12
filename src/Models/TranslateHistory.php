<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 */

namespace Juzaweb\Translations\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Model;
use Juzaweb\Translations\Enums\TranslateHistoryStatus;

class TranslateHistory extends Model
{
    protected $table = 'translate_histories';

    protected $fillable = [
        'translateable_type',
        'translateable_id',
        'locale',
        'status',
        'error',
    ];

    protected $casts = [
        'status' => TranslateHistoryStatus::class,
        'error' => 'array',
    ];

    public function translateable(): MorphTo
    {
        return $this->morphTo();
    }
}
