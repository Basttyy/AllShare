<?php

use FastRoute\Dispatcher;
use FastRoute\Dispatcher\GroupCountBased;
use FastRoute\RouteCollector;
use Psr\Http\Message\ServerRequestInterface;
Use React\Http\Message\Response;

final class Router
{
    private $dispatcher;

    public function __construct(RouteCollector $routes)
    {
        $this->dispatcher = new GroupCountBased($routes->getData());
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $routeinfo = $this->dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());

        switch ($routeinfo[0]) {
            case Dispatcher::NOT_FOUND:
                return Response::json(['message' => 'Not found'])->withStatus(Response::STATUS_NOT_FOUND);
            case Dispatcher::METHOD_NOT_ALLOWED:
                return Response::json(['message' => 'Method not allowed'])->withStatus(Response::STATUS_METHOD_NOT_ALLOWED);              
            case Dispatcher::FOUND:
                return $routeinfo[1]($request, ... array_values($routeinfo[2]));
        }

        throw new LogicException('Something wrong with routing');
    }
}