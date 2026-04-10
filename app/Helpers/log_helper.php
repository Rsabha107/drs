<?php

use Illuminate\Support\Facades\Log;

if (! function_exists('appLog')) {
    /**
     * Custom application log function.
     *
     * @param string $message
     * @param array $context
     * @param string $level
     * @return void
     */
    function appLog(string $message, array $context = [], string $level = 'info'): void
    {
        if (config('logging.enabled')) {
            Log::$level($message, $context);
        }
    }
}
