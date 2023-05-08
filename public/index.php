<?php declare(strict_types=1);

use App\Controllers\CharController;
use App\TwigView;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require '../vendor/autoload.php';

$loader = new FilesystemLoader(__DIR__ . '/../App/Views');
$twig = new Environment($loader);

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $router) {
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
    case FastRoute\Dispatcher::NOT_FOUND:
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        [$controllerName, $methodName] = $handler; // array_extract from handler

        /** @var TwigView $response */
        $response = (new $controllerName)->{$methodName}();

        echo $twig->render($response->getPath() .'.html.twig', $response->getData());

        break;
}

