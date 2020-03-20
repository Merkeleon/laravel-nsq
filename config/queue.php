<?php

return [
    'driver' => 'nsq',

    'default' => 'default',

    'consumer' => env('NSQ_CONSUMER_CONNECTION', 'socket'),//allowed socket
    'producer' => 'curl',//env('NSQ_PRODUCER_CONNECTION', 'socket'),//allowed socket, curl
    'connections' => [
        'socket' => [
            'driver' => 'socket',

            'pub_addresses' => explode(',', 'nsqd:4150'),//nsqd
            'sub_addresses' => 'nsqlookupd:4161',//lookuper
            'nsq_config' => [
                'channel' => 'web'
                //another params like heartbeat, timeouts, user_agent and others
            ],
        ],
        'curl' => [
            'driver' => 'curl',
            'uri' => env('NSQ_CURL_URI', 'http://nsqd:4150'),
        ]
    ],
];
