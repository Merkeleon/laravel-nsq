<?php


namespace Merkeleon\Nsq\src\Drivers\Consumer;


use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Manager;

class ConsumerManager extends Manager
{
    public function __construct(Container $container)
    {
        parent::__construct($container);

        $this->config = collect($this->config->get('queue.connections.nsq.connections'));
    }

    public function createSocketDriver() {

        $socket = new Socket($this->container, $this->config->get('socket'));

        return $socket;
    }

    public function getDefaultDriver()
    {
        return $this->createSocketDriver();
    }
}