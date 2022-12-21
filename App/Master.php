<?php

require __DIR__ .'\..\vendor\autoload.php';
//require 'App/Libraries/Helpers/ConnectionsPool.php';


use React\Socket\ConnectionInterface;
use React\Socket\SocketServer;
use React\Socket\LimitingServer;
// use App\Libraries\Helpers\ConnectionsPool;

$socket = new SocketServer(isset($argv[1]) ? $argv[1] : '127.0.0.1:0', array(
    'tls' => array(
        'local_cert' => isset($argv[3]) ? $argv[3] : (__DIR__ . '/localhost.pem')
    )
));

$socket = new LimitingServer($socket, null);
//$pool = new ConnectionsPool($socket);

$socket->on('connection', function (ConnectionInterface $connection) use ($socket) {
    echo '[' . $connection->getRemoteAddress() . ' connected]' . PHP_EOL;

    //$pool->add($connection);
});

$socket->on('error', function (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
});

echo 'Listening on ' . $socket->getAddress() . PHP_EOL;

