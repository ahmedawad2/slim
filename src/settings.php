<?php

$app_id = 'myAppId'; // The ID of the the parse app
$master_key = 'KSJFKKJ3K4JK3J4K3JUWE89ISDJHFSJDFS';  //The Master Key of the app
$Server_URL = 'http://127.0.0.1:1337'; //Server URL of the parse Server


return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        'ParseClient' => [
            'init' => \Parse\ParseClient::initialize($app_id, null, $master_key),
            'URL' => \Parse\ParseClient::setServerURL($Server_URL, 'parse'),
        ],
    ],
];
