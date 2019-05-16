<?php

namespace placer\tomos;

use DateTime;
use mako\chrono\Time;
use mako\utility\Str;
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
            ->with('name', $user->getUsername())
            ->to($user->getEmail(), $user->getUsername())
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

        $request    = $this->container->get('request');
        $connection = $this->container->get('database');

        $headers   = $request->getHeaders();
        $userAgent = $headers->has('User-Agent')
            ? substr((string) $headers->get('User-Agent'), 0, 500)
            : '';

        $connection->table('tomos_activity')
            ->insert([
                'user_id'     => $user->getId(),
                'action'      => $action,
                'ip_address'  => $request->getIp(),
                'user_agent'  => $userAgent,
                'created_at'  => Time::now()
            ]);
    }

    /**
     * Generates upload path for file
     *
     * @return string
     */
    public function generateFilePath()
    {
        $prefix = (new DateTime)->format('m/d/H/');
        $suffix = $this->getRandomString();
        return $prefix . $suffix;
    }

    /**
     * Generates random string
     *
     * @param  int|integer $length
     * @return string
     */
    public function getRandomString(int $length = 24)
    {
        return substr(str_shuffle("0123456789abcdefhklmnorstuvwxz"), 0, $length);
    }

    /**
     * Getter for options
     *
     * @param $string $key
     * @return mixed
     */
    public function __get(string $key)
    {
        $key = Str::camel2underscored($key);

        return $this->options[$key] ?? null;
    }

}
