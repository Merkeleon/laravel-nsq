<?php


namespace Merkeleon\Nsq\src\Drivers\Producer;


interface ProducerInterface
{
    public function publish($queue, $payload, $delay = 0);
}