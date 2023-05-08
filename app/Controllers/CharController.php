<?php declare(strict_types=1);

namespace App\Controllers;

use App\ApiClient;
use App\TwigView;

class CharController
{
    private ApiClient $client;

    public function __construct()
    {
        $this->client = new ApiClient();
    }

    public function index(): TwigView
    {
        return new TwigView('characters', ['characters' => $this->client->fetchCharacters()]);
    }

}

//$controller = new CharController();
//$characters = $controller->characters();