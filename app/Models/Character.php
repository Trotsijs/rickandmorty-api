<?php declare(strict_types=1);

namespace App\Models;

class Character
{
    private string $name;
    private string $picture;
    private string $status;
    private string $species;
    private string $lastLocation;
    private string $episode;

    public function __construct
    (
        string $name,
        string $picture,
        string $status,
        string $species,
        string $lastLocation,
        string $episode
    ) {
        $this->name = $name;
        $this->picture = $picture;
        $this->status = $status;
        $this->species = $species;
        $this->lastLocation = $lastLocation;
        $this->episode = $episode;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPicture(): string
    {
        return $this->picture;
    }

    public function getSpecies(): string
    {
        return $this->species;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getLastLocation(): string
    {
        return $this->lastLocation;
    }

    public function getEpisode(): string
    {
        return $this->episode;
    }

}