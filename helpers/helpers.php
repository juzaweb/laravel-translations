<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/laravel-translations
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

function custom_var_export($expression): string
{
    $export = var_export($expression, true);
    $export = preg_replace("/^([ ]*)(.*)/m", '$1$1$2', $export);
    $array = preg_split("/\r\n|\n|\r/", $export);
    $array = preg_replace(
        ["/\s*array\s\($/", "/\)(,)?$/", "/\s=>\s$/"],
        [null, ']$1', ' => ['],
        $array
    );
    return implode(PHP_EOL, array_filter(["["] + $array));
}
