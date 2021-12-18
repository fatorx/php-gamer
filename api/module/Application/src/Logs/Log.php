<?php

namespace Application\Logs;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

trait Log
{
    public  $pathLogs = __DIR__ . '/../../../../data/logs/';

    /**
     * @param $message
     * @param string $prefix
     */
    public function logError($message, string $prefix = 'error_')
    {
        $date = new \Datetime();

        // create a log channel
        $log = new Logger('App');
        $hourControl = $date->format('Y-m-d-H');
        $fileName = $prefix . $hourControl.'.txt';

        $log->pushHandler(new StreamHandler($this->pathLogs . $fileName, Logger::WARNING));

        // add records to the log
        $headers = getallheaders();
        $clientIp = (isset($headers['X-Forwarded-For']) ? $headers['X-Forwarded-For'] : '127.0.0.1');
        $log->error($clientIp . ' - ' . $message);
    }
}
