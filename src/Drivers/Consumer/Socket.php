<?php


namespace Merkeleon\Nsq\src\Drivers\Consumer;


use Merkeleon\Nsq\src\NsqJob;
use Nsq;
use NsqLookupd;
use NsqMessage;

class Socket implements ConsumerInterface
{
    protected $nsq;
    protected $nsqLookupd;
    protected $container;

    public function __construct($container, array $config)
    {
        $this->nsqLookupd = new NsqLookupd($config['sub_addresses']);
        $this->nsq = new Nsq($config['nsq_config']);
        $this->container = $container;
    }

    public function subscribe($queue, $callback)
    {
        $config = array(
            "topic" => $queue,
            "channel" => "web",
            "rdy" => 2,                //optional , default 1
            "connect_num" => 1,        //optional , default 1
            "retry_delay_time" => 5000,  //optional, default 0 , if run callback failed, after 5000 msec, message will be retried
            "auto_finish" => true, //default true
        );
        $this->nsq->subscribe($this->nsqLookupd, $config, function(NsqMessage $msg,$bev) use ($callback, $queue) {
            $job = new NsqJob($this->container, $msg, 'nsq', $queue);
            $callback($job);
        });
    }
}