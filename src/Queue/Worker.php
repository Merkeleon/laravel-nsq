<?php

namespace Merkeleon\Nsq\Queue;


use Illuminate\Queue\Worker as BaseWorker;
use Illuminate\Queue\WorkerOptions;
use Merkeleon\Nsq\src\NsqJob;

class Worker extends BaseWorker
{
    public function daemon($connectionName, $queue, WorkerOptions $options)
    {
        $connection = $this->manager->connection($connectionName);
        if ($connection instanceof NsqQueue) {

            $connection->subscribe($queue, function(NsqJob $job) use ($connectionName, $options) {
                $this->runJob($job, $connectionName, $options);
            });

            return;
        }

        parent::daemon(...func_get_args());
    }

    protected function registerTimeoutHandler($job, WorkerOptions $options)
    {
        return false;
    }
}
