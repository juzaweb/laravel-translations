<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Translations;

use IP2Location\Database;
use RuntimeException;

class IP2LocationFactory implements Contracts\IP2Location
{
    public function __construct(protected string $dataPath)
    {
        if (!file_exists($this->dataPath)) {
            throw new RuntimeException("IP2Location database file not found at {$this->dataPath}");
        }
    }

    public function lookup(string $ip, array|int $fields = null)
    {
        return (new Database($this->dataPath, Database::FILE_IO))->lookup($ip, $fields);
    }
}
