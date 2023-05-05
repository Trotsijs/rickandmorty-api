<?php declare(strict_types=1);

namespace App;

use App\Models\Character;
use GuzzleHttp\Client;
use stdClass;

class ApiClient
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }


    public function fetchCharacters(): array
    {
        $url = 'https://rickandmortyapi.com/api/character/';
        $response = $this->client->request('GET', $url);

        return json_decode($response->getBody()->getContents())->results;

    }

    public function createCharacter(stdClass $character): Character
    {
        return new Character(
            $character->name,
            $character->image,
            $character->status,
            $character->species,
            $character->location->name,
            $character->origin->name
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