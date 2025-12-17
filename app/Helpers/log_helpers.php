<?php

use Illuminate\Support\Facades\File;

if (! function_exists('writeCustomLog')) {
    /**
     * Backwards-compatible helper that writes to storage and public logs.
     */
    function writeCustomLog(string $message): void
    {
        // Primary: storage/logs/custom.log via CustomLogger
        try {
            \App\Utils\CustomLogger::write($message);
        } catch (Throwable $e) {
            // swallow errors
        }

        // Legacy: also append to public/logs/custom.log for compatibility
        try {
            $publicPath = public_path('logs/custom.log');
            $dir = dirname($publicPath);
            if (! File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
            $timestamp = now()->toDateTimeString();
            File::append($publicPath, $timestamp . ' | ' . $message . PHP_EOL);
        } catch (Throwable $e) {
            // ignore
        }
    }
}
