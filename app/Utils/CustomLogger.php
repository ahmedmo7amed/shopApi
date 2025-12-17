<?php

namespace App\Utils;

use Illuminate\Support\Facades\File;

class CustomLogger
{
    /**
     * Append a message to storage/logs/custom.log (creates dir/file if missing).
     */
    public static function write(string $message): void
    {
        $path = storage_path('logs/custom.log');

        $dir = dirname($path);
        if (! File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $timestamp = now()->toDateTimeString();
        File::append($path, $timestamp . ' | ' . $message . PHP_EOL);
    }
}
