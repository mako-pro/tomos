<?php

namespace placer\tomos;

use mako\application\Package;

class TomosPackage extends Package
{
    /**
     * Package name.
     *
     * @var string
     */
    protected $packageName = 'placer/tomos';

    /**
     * Package namespace.
     *
     * @var string
     */
    protected $fileNamespace = 'tomos';

    /**
     * {@inheritdoc}
     */
    protected function bootstrap(): void
    {
        $options = $this->container->get('config')->get('tomos::config');

        $routes = $this->container->get('routes');

        include $this->getPath() . '/routing/routes.php';

        $this->container->registerSingleton([Tomos::class, 'tomos'], function () use($options)
        {
            return new Tomos($this->container, $options);
        });
    }

}
