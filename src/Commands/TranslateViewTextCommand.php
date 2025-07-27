<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Translations\Commands;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Console\Input\InputArgument;

class TranslateViewTextCommand extends Command
{
    protected $name = 'translate:views';

    protected $description = 'Wrap plain text in Blade views with the __() translation function';

    public function handle(): int
    {
        $path = base_path($this->argument('path'));

        if (!is_dir($path)) {
            $this->error("Directory not found: $path");
            return 1;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path)
        );

        foreach ($iterator as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $filePath = $file->getRealPath();
            $original = file_get_contents($filePath);
            $updated = $this->replacePlainText($original);

            if ($original !== $updated) {
                file_put_contents($filePath, $updated);
                $this->info("Translated: " . $filePath);
            }
        }

        $this->info("✅ Done.");
        return 0;
    }

    protected function replacePlainText($content): array|string|null
    {
        return preg_replace_callback(
            '/>([^<]+?)</',
            function ($matches) {
                $text = trim($matches[1]);

                if ($text === '') {
                    return $matches[0];
                }
                if (str_contains($text, '{{') || str_contains($text, '__(')) {
                    return $matches[0];
                }

                // Escape quotes properly
                $escaped = str_replace("'", "\\'", $text);

                return '>{{ __(\''
                    . $escaped
                    . '\') }}<';
            },
            $content
        );
    }

    protected function getArguments(): array
    {
        return [
            ['path', InputArgument::REQUIRED, 'The path to the Blade views directory'],
        ];
    }
}
