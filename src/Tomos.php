<?php

namespace placer\tomos;

use mako\syringe\Container;

class Tomos
{
    /**
     * Container.
     *
     * @var \mako\syringe\Container
     */
    protected $container;

    /**
     * Constructor
     *
     * @param Container  $container  IoC container instance
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Getter for options
     *
     * @param $string $key
     * @return mixed
     */
    public function option(string $key)
    {
        $config = $this->container->get('config');

        return $config->get('tomos::config')[$key] ?? null;
    }

}
