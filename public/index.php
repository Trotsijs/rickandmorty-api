<?php declare(strict_types=1);

use App\Controllers\Controller;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require '../vendor/autoload.php';

$loader = new FilesystemLoader(__DIR__ . '../../App/Views');
$twig = new Environment($loader);

$controller = new Controller();
$characters = $controller->characters();

echo $twig->render('content.html.twig', ['characters' => $characters]);