<?php
require_once '../config/Config.php';

class Logger
{
    public static function log($message, $context = 'System')
    {
        $date = date('Y-m-d H:i:s');
        $logEntry = "[{$date}] [{$context}] {$message}" . PHP_EOL;
        error_log($logEntry, 3, Config::LOG_FILE);
    }

    public static function write(Exception $e, $context = '')
    {
        $message = $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine();
        self::log($message, $context);
    }

    public static function safeRun(callable $callback, $redirectUrl, $context = 'System')
    {
        try {
            return $callback();
        } catch (Exception $e) {
            self::write($e, $context);
            
            $separator = (strpos($redirectUrl, '?') !== false) ? '&' : '?';
            header("Location: " . $redirectUrl . $separator . "error=server_error");
            exit;
        }
    }
}