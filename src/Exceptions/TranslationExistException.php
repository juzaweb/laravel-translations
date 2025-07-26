<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Translations\Exceptions;

class TranslationExistException extends TranslationException
{
    public static function make(): static
    {
        return new static(
            __('The translation already exists.')
        );
    }
}
