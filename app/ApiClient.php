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

    public function fetchCharacters(string $pageNumber): array
    {

        try {

            $characterCollection = [];
            $page = '/?page=';

            if (!Cache::has('characters' . $pageNumber)) {
                $response = $this->client->get(self::URL . '/character' . $page . $pageNumber);
                $responseJson = $response->getBody()->getContents();
                Cache::remember('characters' . $pageNumber, $responseJson);
            } else {
                $responseJson = Cache::get('characters' . $pageNumber);
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

    public function fetchLocations(string $pageNumber): array
    {
        try {
            if (!Cache::has('locations' . $pageNumber)) {
                $url = self::URL . '/location';
                $page = '/?page=';
                $response = $this->client->request('GET', $url . $page . $pageNumber);
                $responseJson = $response->getBody()->getContents();
                Cache::remember('locations' . $pageNumber, $responseJson);
            } else {
                $responseJson = Cache::get('locations' . $pageNumber);
            }


            $locations = json_decode($responseJson);

            $locationCollection = [];

            foreach ($locations->results as $location) {
                $locationCollection[] = new Location($location->name, $location->type, $location->dimension);
            }

            return $locationCollection;

        } catch (GuzzleException $exception) {
            return [];
        }
    }

    public function fetchEpisodes(string $pageNumber): array
    {
        try {

            $url = self::URL . '/episode';
            $page = '/?page=';

            if (!Cache::has('episodes' . $pageNumber)) {
                $response = $this->client->request('GET', $url . $page . $pageNumber);
                $responseJson = $response->getBody()->getContents();
                Cache::remember('episodes' . $pageNumber, $responseJson);
            } else {
                $responseJson = Cache::get('episodes' . $pageNumber);
            }

            $episodes = json_decode($responseJson);

            $episodeCollection = [];

            foreach ($episodes->results as $episode) {
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

            if (!Cache::has('character_' . $character)) {
                $response = $this->client->request('GET', $url);
                $responseJson = $response->getBody()->getContents();
                Cache::remember('character_' . $character, $responseJson);
            } else {
                $responseJson = Cache::get('character_' . $character);
            }

            $characters = json_decode($responseJson);

            $characterCollection = [];

            foreach ($characters->results as $character) {
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