<?php declare(strict_types=1);

namespace App\Models;

class Location
{
    private string $name;
    private string $type;
    private string $dimension;

    public function __construct(string $name, string $type, string $dimension)
    {
        $this->name = $name;
        $this->type = $type;
        $this->dimension = $dimension;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDimension(): string
    {
        return $this->dimension;
    }

    public function getType(): string
    {
        return $this->type;
    }
}