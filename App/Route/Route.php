<?php

require 'vendor/autoload.php';
include 'helpers/Validator.php';

use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;
use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use React\EventLoop\Loop;
use React\Http\Client\Request;
use React\Http\Message\ServerRequest;
use React\Promise\PromiseInterface;
use function React\Async\await;
use function React\Async\async;
use function React\Promise\Timer\sleep;

$routes = new RouteCollector(new Std(), new GroupCountBased());

$routes->addGroup('/account', function (RouteCollector $routes) use ($getAccountHistory, $getAccountInfo, $getSettings) {
    $routes->get('', $getAccountInfo);
    $routes->get('/history', $getAccountHistory);
    $routes->get('/settings', $getSettings);
});