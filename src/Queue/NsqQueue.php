<?php

namespace Merkeleon\Nsq\Queue;

use Illuminate\Contracts\Queue\Queue as QueueContract;
use Illuminate\Queue\Queue;
use Merkeleon\Nsq\src\Drivers\Consumer\ConsumerManager;
use Merkeleon\Nsq\src\Drivers\Producer\ProducerManager;

class NsqQueue extends Queue implements QueueContract
{
    protected $default;
    protected $config;
    protected $consumer;
    protected $producer;

    public function __construct($config)
    {
        $this->default = $config['default'];
        $this->config = $config;
    }

    public function size($queue = null)
    {
        return 0; //TODO: find way to get count of messages
    }

    public function push($job, $data = '', $queue = null)
    {
        return $this->pushToNsq($queue, $this->createPayload(
            $job, $this->getQueue($queue), $data
        ));
    }

    public function pushRaw($payload, $queue = null, array $options = [])
    {
        return $this->pushToNsq($queue, $payload);
    }

    public function later($delay, $job, $data = '', $queue = null)
    {
        return $this->pushToNsq($queue, $this->createPayload(
            $job, $this->getQueue($queue), $data
        ), $delay);
    }

    public function bulk($jobs, $data = '', $queue = null)
    {
        throw new \Exception('Need implement bulk push messages');
    }

    protected function pushToNsq($queue, $payload, $delay = 0, $attempts = 0)
    {
        $manager = new ProducerManager($this->container);
        $driver = $manager->driver($this->config['producer']);
        $driver->publish($queue, $payload, $delay, $attempts);
    }

    public function pop($queue = null, callable $callback = null)
    {

    }

    public function subscribe($queue = null, callable $callback = null)
    {
        $manager = new ConsumerManager($this->container);
        $driver = $manager->driver($this->config['consumer']);

        $driver->subscribe($this->getQueue($queue), $callback);
    }

    public function getQueue($queue)
    {
        return $queue ?: $this->default;
    }
}
