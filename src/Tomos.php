<?php

namespace placer\tomos;

use mako\chrono\Time;
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
     * User activity logging
     *
     * @return void
     */
    public function touchActivity(string $action)
    {
        $gatekeeper = $this->container->get('gatekeeper');

        if (! $user = $gatekeeper->getUser())
        {
            return;
        }

        $request    = $this->container ->get('request');
        $connection = $this->container->get('database');
        $i18n       = $this->container->get('i18n');

        $headers    = $request->getHeaders();
        $uaserAgent = $headers->has('User-Agent')
            ? substr((string) $headers->get('User-Agent'), 0, 500)
            : '';

        $connection->table('tomos_activity')
            ->insert([
                'user_id'     => $user->id,
                'description' => $i18n->get("tomos::activity.{$action}"),
                'ip_address'  => $request->getIp(),
                'user_agent'  => $uaserAgent,
                'created_at'  => Time::now()
            ]);
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
