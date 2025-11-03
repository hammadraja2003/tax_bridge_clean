<?php

namespace App\Logging;

use AsyncAws\CloudWatchLogs\CloudWatchLogsClient;
use AsyncAws\Monolog\CloudWatch\CloudWatchLogsHandler;
use Monolog\Logger;

class CloudWatchLoggerFactory
{
    public function __invoke(array $config)
    {
        $client = new CloudWatchLogsClient([
            'region' => $config['region'],
            'accessKeyId' => $config['key'],
            'accessKeySecret' => $config['secret'],
        ]);

        $options = [
            'log_group_name'  => $config['group'] ?? 'laravel-app-log',
            'log_stream_name' => $config['stream'] ?? 'production',
            'retention'       => 14, // days to retain logs in CloudWatch
        ];

        $handler = new CloudWatchLogsHandler(
            $client,
            $options,
            Logger::toMonologLevel($config['level'] ?? 'info')
        );

        $logger = new Logger('cloudwatch');
        $logger->pushHandler($handler);

        return $logger;
    }
}
