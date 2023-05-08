<?php declare(strict_types=1);

namespace App;

use App\Models\Character;
use GuzzleHttp\Client;
use stdClass;

class ApiClient
{
    private Client $client;
    private const URL = 'https://rickandmortyapi.com/api';

    public function __construct()
    {
        $this->client = new Client();
    }

    public function fetchCharacters(): array
    {
        $page = rand(1, 40);
        $url = self::URL .'/character?page=' . $page;
        $response = $this->client->request('GET', $url);

        return json_decode($response->getBody()->getContents())->results;
    }

    public function fetchEpisodes() {
        $url = self::URL . '/episode';
        $response = $this->client->request('GET', $url);
        return json_decode($response->getBody()->getContents());
    }

    public function createCharacter(stdClass $character): Character
    {
        $episode = $this->client->get($character->episode[0])->getBody()->getContents();
        return new Character(
            $character->name,
            $character->image,
            $character->status,
            $character->species,
            $character->location->name,
            json_decode($episode)->episode
        );
    }

    public function createCollection(): array
    {
        $characters = $this->fetchCharacters();
        $charCollection = [];
        foreach ($characters as $character) {
            $charCollection[] = $this->createCharacter($character);
        }

        return $charCollection;
    }
}