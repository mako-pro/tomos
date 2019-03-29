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
    protected function bootstrap()
    {
        $this->container->registerSingleton([Tomos::class, 'tomos'], function ()
        {
            return new Tomos($this->container);
        });
    }

}
