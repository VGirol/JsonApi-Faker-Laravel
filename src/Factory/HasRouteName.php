<?php

declare(strict_types=1);

namespace VGirol\JsonApiFaker\Laravel\Factory;

trait HasRouteName
{
    /**
     * The route name used to create the "self" link
     *
     * @var string|null
     */
    public $routeName;


    /**
     * Set the route name
     *
     * @param string|null $routeName
     *
     * @return static
     */
    public function setRouteName(?string $routeName)
    {
        $this->routeName = $routeName;

        return $this;
    }
}
