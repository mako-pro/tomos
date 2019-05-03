<?php

namespace app\routing\middleware\tomos;

use Closure;
use mako\http\Request;
use mako\http\Response;
use mako\session\Session;
use mako\http\routing\middleware\MiddlewareInterface;

class AjaxMiddleware implements MiddlewareInterface
{
    /**
     * Session
     *
     * @var \mako\session\Session
     */
    protected $session;

    /**
     * Constructor
     *
     * @param \mako\session\Session $session Session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Request $request, Response $response, Closure $next): Response
    {
        if (! $request->isAjax())
        {
            $response->setStatus('400');

            $response->setBody('Bad Request 400!');

            return $response;
        }

        $header = 'X-CSRF-TOKEN';

        $headers = $request->getHeaders();

        $token = $this->session->getToken();

        if (! $headers->has($header) || $headers->get($header) !== $token)
        {
            $response->setStatus('403');

            $response->setBody('Forbidden 403!');

            return $response;
        }

        return $next($request, $response);
    }

}
