<?php

return [
    'default' => 'default',
    
    'connections' => [
        'default' => [
            'host' => env('NEO4J_HOST', 'localhost'),
            'port' => env('NEO4J_PORT', 7474),
            'username' => env('NEO4J_USERNAME', 'neo4j'),
            'password' => env('NEO4J_PASSWORD', ''),
            'scheme' => env('NEO4J_SCHEME', 'http'),
            'database' => env('NEO4J_DATABASE', 'neo4j'),
        ],
    ],
]; 