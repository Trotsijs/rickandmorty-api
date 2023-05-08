<?php declare(strict_types=1);

namespace App\Core;

use App\Controllers\CharController;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;

class Router
{
    public static function response(): ?TwigView
    {
        $dispatcher = \FastRoute\simpleDispatcher(function (RouteCollector $router) {
            $router->addRoute('GET', '/', [CharController::class, 'index']);
            $router->addRoute('GET', '/characters', [CharController::class, 'index']);
        });

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                return null;

            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];

                return null;

            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                [$controllerName, $methodName] = $handler;

                /** @var TwigView $response */
                $response = (new $controllerName)->{$methodName}();

                return $response;

        }

        return null;
    }
}