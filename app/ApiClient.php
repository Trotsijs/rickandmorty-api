<?php declare(strict_types=1);

namespace App;

use App\Models\Character;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

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
        try {
            $page = '?page=' . rand(1, 40);
            $url = self::URL . '/character' . $page;
            $response = $this->client->request('GET', $url);

            $characters = json_decode($response->getBody()->getContents())->results;

            $characterCollection = [];

            foreach ($characters as $character) {
                $episode = $this->client->get($character->episode[0])->getBody()->getContents();
                $characterCollection[] = new Character
                (
                    $character->name,
                    $character->image,
                    $character->status,
                    $character->species,
                    $character->location->name,
                    json_decode($episode)->episode
                );
            }

            return $characterCollection;

        } catch (GuzzleException $exception) {
            return [];
        }
    }
}