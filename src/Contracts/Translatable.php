<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 */

namespace Juzaweb\Translations\Contracts;

interface Translatable extends \Astrotomic\Translatable\Contracts\Translatable
{
    public function getTranslatedFields(): array;
}
