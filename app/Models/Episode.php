<?php declare(strict_types=1);

namespace App\Models;

class Episode
{
    private string $name;
    private string $airDate;
    private string $season;

    public function __construct($name, $airDate, $season)
    {
        $this->name = $name;
        $this->airDate = $airDate;
        $this->season = $season;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAirDate(): string
    {
        return $this->airDate;
    }

    public function getSeason(): string
    {
        return $this->season;
    }

}