<?php

//this will output log directly into server console
if (!function_exists('consoleLog')) {
    function consoleLog($data): void
    {
        $logs = $_ENV['CONSOLE_LOGS'] ?? getenv('CONSOLE_LOGS');
        if ($logs) error_log("\033[31m" . print_r($data, true) . "\033[0m");
    }
}