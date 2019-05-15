<?php
declare (strict_types = 1);

namespace VGirol\JsonApiAssert\Laravel\Factory;

trait HasRouteName
{
    /**
     * Undocumented variable
     *
     * @var string
     */
    protected $routeName;


    /**
     * Undocumented function
     *
     * @param string|null $routeName
     * @return static
     */
    public function setRouteName(?string $routeName)
    {
        $this->routeName = $routeName;

        return $this;
    }
}
