<?php
require 'App/Master';
//require 'App/Libraries/Helpers/helpers.php';
require 'App/Routes/Router.php';

use React\Http\HttpServer;
use Psr\Http\Message\ServerRequestInterface;
use React\Socket\SocketServer;
use \Ratchet\Client;
use \Ratchet\RFC6455\Messaging\MessageInterface;
use App\Routes\Router;

use function Ratchet\Client\connect;
use function React\Async\await;

$loggingMiddleware = function (ServerRequestInterface $request, callable $next) {
    return $next($request);
};

// $websocket_route = new WebsocketRoute($pool);

// $websocket_route->connect("ws://localhost:23457/realtime-feed", [], ['Origin' => 'http://localhost:23457']);

$router = new Router($routes);

$http = new HttpServer(
    $loggingMiddleware,
    $router
);

$socket2 = new SocketServer(isset($argv[2]) ? $argv[2] : '127.0.0.1:23459', array(
    'tls' => array(
        'local_cert' => isset($argv[3]) ? $argv[3] : (__DIR__ . '/localhost.pem')
    )
));

$http->listen($socket2);

echo 'Http server Listening on ' . str_replace('tcp:', 'http:', $socket2->getAddress()) . PHP_EOL;
