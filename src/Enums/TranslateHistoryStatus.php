<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/laravel-translations
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 */

namespace Juzaweb\Translations\Enums;

enum TranslateHistoryStatus: string
{
    case PENDING = 'pending';
    case SUCCESS = 'success';
    case FAILED = 'failed';

    /**
     * Get all statuses as an array.
     *
     * @return array
     */
    public function all(): array
    {
        return [
            self::PENDING->value => __('Pending'),
            self::SUCCESS->value => __('Success'),
            self::FAILED->value => __('Failed'),
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::PENDING => __('Pending'),
            self::SUCCESS => __('Success'),
            self::FAILED => __('Failed'),
        };
    }

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }

    public function isSuccess(): bool
    {
        return $this === self::SUCCESS;
    }

    public function isFailed(): bool
    {
        return $this === self::FAILED;
    }
}
