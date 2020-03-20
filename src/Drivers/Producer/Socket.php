<?php


namespace Merkeleon\Nsq\src\Drivers\Producer;


use Nsq;

class Socket implements ProducerInterface
{
    protected $nsq;
    protected $nsqAddresses;

    public function __construct(array $config)
    {
        $this->nsqAddresses = $config['pub_addresses'];
        $this->nsq = new Nsq($config['nsq_config']);
    }

    protected $isConnected = false;
    public function connect()
    {
        if (!$this->isConnected)
        {
            $this->isConnected = $this->nsq->connectNsqd($this->nsqAddresses);
        }
    }

    public function publish($queue, $payload, $delay = 0)
    {
        if ($delay >= 0) {
            return $this->nsq->deferredPublish($queue, $payload, $delay);
        }
        return $this->nsq->publish($queue, $payload);
    }
}