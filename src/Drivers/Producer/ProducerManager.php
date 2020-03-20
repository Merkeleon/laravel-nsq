<?php


namespace Merkeleon\Nsq\src\Drivers\Producer;


use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Manager;

class ProducerManager extends Manager
{
    public function __construct(Container $container)
    {
        parent::__construct($container);

        $this->config = collect($this->config->get('queue.connections.nsq.connections'));
    }

    public function createCurlDriver() {
        return new Curl($this->config->get('curl'));
    }

    public function createSocketDriver() {

        $socket = new Socket($this->config->get('socket'));
        $socket->connect();

        return $socket;
    }

    public function getDefaultDriver()
    {
        return $this->createSocketDriver();
    }
}