<?php declare(strict_types=1);

namespace App\Controllers;

use App\ApiClient;
use App\Core\TwigView;

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

    public function locations(): TwigView
    {
        return new TwigView('locations', ['locations' => $this->client->fetchLocations()]);
    }

    public function episodes(): TwigView
    {
        return new TwigView('episodes', ['episodes' => $this->client->fetchEpisodes()]);
    }

    public function search(): TwigView
    {
        $name = $_GET['search'];
        return new TwigView('characters', ['characters' => $this->client->searchCharacters($name)]);
    }
}