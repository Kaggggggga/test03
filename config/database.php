<?php
$redisEndpoints = env("REDIS_HOSTS", "127.0.0.1");

return [
    'redis' => [
        'cluster' => true,

        'default' => array_filter(array_map(function($host){
            if(empty($host)){
                return null;
            }
            return [
                'host' => $host,
                'password' => env('REDIS_PASSWORD', null),
                'port' => env('REDIS_PORT', 6379),
                'database' => env('REDIS_DATABASE', 1),
            ];
        }, explode(",", env("REDIS_HOSTS", "127.0.0.1")))),

        'options' => [
            'cluster' => 'redis',
        ],
    ],
];