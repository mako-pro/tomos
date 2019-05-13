<?php

namespace placer\tomos\controllers\auth;

use mako\http\routing\Controller;

class ResetPasswordController extends Controller
{
    /**
     * Before action
     *
     * @return mixed
     */
    public function beforeAction()
    {
        if ($this->gatekeeper->isLoggedIn())
        {
            $route = 'tomos.dashboard.page';

            $url = $this->urlBuilder->toRoute($route);

            if (! $this->request->isAjax())
            {
                return $this->redirectResponse($url);
            }

            return $this->jsonResponse(['url' => $url]);
        }
    }

    /**
     * Loads the reset password form by token
     *
     * @param  string $token Action token
     * @return Response
     */
    public function page($token)
    {
        $rules = $this->config->get('tomos::rules.action');

        $validator = $this->validator->create(['token' => $token], $rules);

        if (! $validator->isValid())
        {
            $this->response->setStatus('403');

            $this->response->setBody('Forbidden 403!');

            return $this->response->send();
        }

        return $this->view->render('tomos::auth.reset', ['token' => $token]);
    }

    /**
     * Resets a user password
     *
     * @return Response
     */
    public function handler()
    {
        $post  = $this->request->getPost();
        $rules = $this->config->get('tomos::rules.action');

        $validator = $this->validator->create($post->all(), $rules);

        if (! $validator->isValid())
        {
            return $this->jsonResponse([
                'message' => $this->i18n->get("tomos::auth.reset.fail")
            ]);
        }

        $user = $this->gatekeeper->getUserRepository()
            ->getByActionToken($post->get('token'));

        if ($user === false)
        {
            return $this->jsonResponse([
                'message' => $this->i18n->get("tomos::auth.reset.fail")
            ]);
        }

        $rules = $this->config->get('tomos::rules.reset');

        $validator = $this->validator->create($post->all(), $rules);

        if (! $validator->isValid())
        {
            $this->response->setStatus('400');

            return $this->jsonResponse([
                'errors'   => true,
                'messages' => $validator->getErrors()
            ]);
        }

        if ($post->get('email') !== $user->getEmail())
        {
            $user->generateActionToken();

            $user->save();

            return $this->jsonResponse([
                'message' => $this->i18n->get("tomos::auth.forgot.bad-email")
            ]);
        }

        $user->setPassword($post->get('password'));

        $user->generateActionToken();

        $user->save();

        $this->session->putFlash(
            'message', $this->i18n->get('tomos::auth.reset.success')
        );

        return $this->jsonResponse([
            'url' => $this->urlBuilder->toRoute('tomos.login.page')
        ]);
    }

}
