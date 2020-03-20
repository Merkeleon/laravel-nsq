<?php


namespace Merkeleon\Nsq\src\Drivers\Producer;


use Exception;

class Curl implements ProducerInterface
{
    protected $nsqdAddress = '';
    public function __construct(array $config)
    {
        $this->nsqdAddress = $config['uri'];
    }

    public function publish($queue, $payload, $delay = 0)
    {
        logger()->info('[CurlTransport] write', get_defined_vars());
        $method = 'pub';
        if (is_array($payload)) {
            $payload = implode("\n", $payload);
            $method = 'mpub';
            $delay = null;//it's impossible use deferTime and mpub together
        }

        $ch = curl_init();

        $params = ['topic' => $queue];
        if ($delay)
        {
            $params['defer'] = $delay;
        }

        $url = $this->nsqdAddress.'/'.$method.'?' . http_build_query($params);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        $headers   = [];
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        if ($result != 'OK') {
            $curlError = curl_error($ch);
            throw new Exception("Unable to write job to {$queue}. Because: {$curlError}");
        }

        return $this;
    }
}