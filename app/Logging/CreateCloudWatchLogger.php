<?php

namespace App\Logging;

use Maxbanton\Cwh\Handler\CloudWatch;
use Monolog\Logger;
use Aws\CloudWatchLogs\CloudWatchLogsClient;

class CreateCloudWatchLogger
{
    public function __invoke(array $config)
    {
        $client = new CloudWatchLogsClient([
            'region' => $config['region'],
            'version' => 'latest',
            'credentials' => $config['credentials'],
        ]);

        $handler = new CloudWatch(
            $client,
            $config['group_name'],
            $config['stream_name'],
            $config['retention'] ?? 14,
            10000, // batch size
            [],
            Logger::DEBUG
        );

        return new Logger('cloudwatch', [$handler]);
    }
}
