<?php

return [
    'enabled' => env('ELASTICSEARCH_ENABLED', true),
    'host' => env('ELASTICSEARCH_HOST', 'https://localhost:9200'),
    'username' => env('ELASTICSEARCH_USERNAME', 'elastic'),
    'password' => env('ELASTICSEARCH_PASSWORD', 'lxzEN2fzqZ2Z+h3PXF6A'),
    'ssl_verification' => env('ELASTICSEARCH_SSL_VERIFICATION', false),
];
