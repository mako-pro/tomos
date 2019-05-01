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
    protected $options;

    /**
     * Constructor
     *
     * @param Container  $container  IoC container instance
     * @param array  $options
     */
    public function __construct(Container $container, $options = [])
    {
        $this->container = $container;
        $this->options   = $options;
    }

    /**
     * Send activation email
     *
     * @param  integer $id User id
     * @return mixed
     */
    public function sendActivationEmail($id)
    {
        $gatekeeper = $this->container->get('gatekeeper');
        $mailer     = $this->container->get('mailer');

        $user = $gatekeeper->getUserRepository()->getById($id);

        $mailer->message('tomos-verification')
            ->with('token', $user->getActionToken())
            ->with('name', $user->username)
            ->to($user->email, $user->username)
            ->send(true);

    }

    /**
     * Getter for options
     *
     * @param $string $key
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->options[$key] ?? null;
    }

}
