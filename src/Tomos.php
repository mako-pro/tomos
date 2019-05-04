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
     * Returns current auth user
     *
     * @return bool|\placer\tomos\models\User
     */
    public function getCurrentUser()
    {
        $gatekeeper = $this->container->get('gatekeeper');

        $user = $gatekeeper->getUser();

        if ($user === null)
        {
            return false;
        }

        // Sanitize the user object

        $userArr = $user->toArray();

        $userArr['profile'] = $user->profile;

        return (object) $userArr;
    }

    /**
     * Send user email
     *
     * @param  integer $id     User id
     * @param  string $action  Action name
     * @return void
     */
    public function sendUserEmail(int $id, string $action)
    {
        $gatekeeper = $this->container->get('gatekeeper');
        $mailer     = $this->container->get('mailer');

        $user = $gatekeeper->getUserRepository()->getById($id);

        $mailer->message("tomos-{$action}")
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
