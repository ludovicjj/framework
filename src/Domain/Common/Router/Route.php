<?php

namespace App\Domain\Common\Router;

class Route
{
    /** @var string */
    private $name;

    /** @var callable|string */
    private $callback;

    /** @var string[] */
    private $parameters;

    public function __construct(
        string $name,
        $callback,
        array $parameters
    ) {
        $this->name = $name;
        $this->callback = $callback;
        $this->parameters = $parameters;
    }

    /**
     * Retrieve route name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return callable|string
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Retrieve URL parameters
     *
     * @return string[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
