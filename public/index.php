<?php declare(strict_types=1);

use App\Controllers\CharController;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require '../vendor/autoload.php';

$loader = new FilesystemLoader(__DIR__ . '../../App/Views');
$twig = new Environment($loader);

$controller = new CharController();
$characters = $controller->characters();

echo $twig->render('content.html.twig', ['characters' => $characters]);