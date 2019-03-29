<?php

namespace placer\tomos;

use mako\syringe\Container;

class Tomos
{
    /**
     * Container
     *
     * @var \mako\syringe\Container
     */
    protected $container;

    /**
     * Options
     *
     * @var array
     */
    protected $options = [
        'register' => false,
        'reset'    => false,
        'verify'   => false,
    ];

    /**
     * Constructor
     *
     * @param Container  $container  IoC container instance
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        $config = $container->get('config')->get('tomos::config');

        $this->options = array_merge($this->options, $config);
    }

    /**
     * Getter for options
     *
     * @param $string $key
     * @return mixed
     */
    public function option(string $key)
    {
        return $this->options[$key] ?? null;
    }

}
