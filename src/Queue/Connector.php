<?php

namespace Merkeleon\Nsq\Queue;

use Illuminate\Queue\Connectors\ConnectorInterface;
use Nsq;

class Connector implements ConnectorInterface
{

    /**
     * Establish a queue connection.
     *
     * @param  array  $config
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        return new NsqQueue($config);
//        return new DatabaseQueue(
//            $this->connections->connection($config['connection'] ?? null),
//            $config['table'],
//            $config['queue'],
//            $config['retry_after'] ?? 60
//        );
    }
}
