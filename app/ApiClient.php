<?php declare(strict_types=1);

namespace App;

use App\Models\Character;
use App\Models\Episode;
use App\Models\Location;
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

            $characterCollection = [];

            if (!Cache::has('characters')) {
                $response = $this->client->get(self::URL . '/character');
                $responseJson = $response->getBody()->getContents();
                Cache::remember('characters', $responseJson);
            } else {
                $responseJson = Cache::get('characters');
            }

            $characters = json_decode($responseJson);

            foreach ($characters->results as $character) {
                $episodeUrl = $character->episode[0];
                $episodeCacheKey = md5($episodeUrl);

                if (!Cache::has($episodeCacheKey)) {
                    $episodeJson = $this->client->get($episodeUrl)->getBody()->getContents();
                    Cache::remember($episodeCacheKey, $episodeJson);
                } else {
                    $episodeJson = Cache::get(md5($episodeUrl));
                }

                $episode = json_decode($episodeJson);

                $characterCollection[] = new Character
                (
                    $character->name,
                    $character->image,
                    $character->status,
                    $character->species,
                    $character->location->name,
                    $episode->episode
                );
            }

            return $characterCollection;

        } catch (GuzzleException $exception) {
            return [];
        }
    }

    public function fetchLocations(): array
    {
        try {
            $page = '?page=' . rand(1, 7);
            $url = self::URL . '/location' . $page;
            $response = $this->client->request('GET', $url);

            $locations = json_decode($response->getBody()->getContents())->results;

            $locationCollection = [];

            foreach ($locations as $location) {
                $locationCollection[] = new Location($location->name, $location->type, $location->dimension);
            }

            return $locationCollection;

        } catch (GuzzleException $exception) {
            return [];
        }
    }

    public function fetchEpisodes(): array
    {
        try {
            $page = '?page=' . rand(1, 3);
            $url = self::URL . '/episode' . $page;
            $response = $this->client->request('GET', $url);

            $episodes = json_decode($response->getBody()->getContents())->results;

            $episodeCollection = [];

            foreach ($episodes as $episode) {
                $episodeCollection[] = new Episode($episode->name, $episode->air_date, $episode->episode);
            }

            return $episodeCollection;

        } catch (GuzzleException $exception) {
            return [];
        }
    }

    public function searchCharacters(string $character): array
    {
        try {
            $url = self::URL . '/character/?name=' . $character;
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