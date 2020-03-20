<?php


namespace Merkeleon\Nsq\src;


use Illuminate\Container\Container;
use Illuminate\Contracts\Queue\Job as JobContract;
use Illuminate\Queue\Jobs\Job;
use NsqMessage;

class NsqJob extends Job implements JobContract
{
    protected $msg;
    public function __construct(Container $container, NsqMessage $msg, $connectionName, $queue)
    {
        $this->msg            = $msg;
        $this->queue          = $queue;
        $this->container      = $container;
        $this->connectionName = $connectionName;
    }
    /**
     * @inheritDoc
     */
    public function getJobId()
    {
        return $this->msg->messageId;
    }

    /**
     * @inheritDoc
     */
    public function getRawBody()
    {
        return $this->msg->payload;
    }

    /**
     * @inheritDoc
     */
    public function attempts()
    {
        return $this->msg->attempts;
    }
}