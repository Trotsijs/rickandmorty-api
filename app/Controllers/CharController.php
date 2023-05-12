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
        $pageNumber = $_GET['page'] ?? '';
        if ($pageNumber == '') {
            $pageNumber = '1';
        }

        return new TwigView('characters', ['characters' => $this->client->fetchCharacters($pageNumber)]);
    }

    public function locations(): TwigView
    {
        $pageNumber = $_GET['locationPage'] ?? '';
        if ($pageNumber == '') {
            $pageNumber = '1';
        }
        return new TwigView('locations', ['locations' => $this->client->fetchLocations($pageNumber)]);
    }

    public function episodes(): TwigView
    {
        $pageNumber = $_GET['episodePage'] ?? '';
        if ($pageNumber == '') {
            $pageNumber = '1';
        }
        return new TwigView('episodes', ['episodes' => $this->client->fetchEpisodes($pageNumber)]);
    }

    public function search(): TwigView
    {
        $name = $_GET['search'];

        return new TwigView('characters', ['characters' => $this->client->searchCharacters($name)]);
    }
}