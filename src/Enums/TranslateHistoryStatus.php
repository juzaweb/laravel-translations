<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 */

namespace Juzaweb\Translations\Enums;

enum TranslateHistoryStatus: string
{
    case PENDING = 'pending';
    case SUCCESS = 'success';
    case FAILED = 'failed';
}
