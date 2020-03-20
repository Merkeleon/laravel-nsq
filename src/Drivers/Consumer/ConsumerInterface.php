<?php


namespace Merkeleon\Nsq\src\Drivers\Consumer;


interface ConsumerInterface
{
    public function subscribe($queue, $callback);
}