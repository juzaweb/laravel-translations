<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Translations\Contracts;

interface Translatable extends \Astrotomic\Translatable\Contracts\Translatable
{
    public function getTranslatedFields(): array;
}
