<?php

namespace placer\tomos\controllers\auth;

use mako\http\routing\Controller;

class ResetPasswordController extends Controller
{
    /**
     * Loads the reset password form by token
     *
     * @param  string $token Action token
     * @return Response
     */
    public function page($token)
    {
        if ($this->gatekeeper->isLoggedIn())
        {
            return $this->redirectResponse('tomos.dashboard.page');
        }

        $rules = $this->config->get('tomos::rules.action');
        $check = $this->validator->create(['token' => $token], $rules);

        if (! $check->isValid())
        {
            $this->response->setStatus('403');

            return 'Forbidden 403!';
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
        if (! $this->request->isAjax())
        {
            return $this->redirectResponse('/');
        }

        if ($this->gatekeeper->isLoggedIn())
        {
            return $this->jsonResponse([
                'url' => $this->urlBuilder->toRoute('tomos.dashboard.page')
            ]);
        }

        $postData   = $this->request->getPost()->all();
        $rules      = $this->config->get('tomos::rules.action');
        $checkToken = $this->validator->create($postData, $rules);

        if (! $checkToken->isValid())
        {
            return $this->jsonResponse([
                'message' => $this->i18n->get("tomos::auth.reset.fail")
            ]);
        }

        $user = $this->gatekeeper->getUserRepository()
            ->getByActionToken($postData['token']);

        if ($user === false)
        {
            return $this->jsonResponse([
                'message' => $this->i18n->get("tomos::auth.reset.fail")
            ]);
        }

        $rules = $this->config->get('tomos::rules.reset');
        $check = $this->validator->create($postData, $rules);

        if (! $check->isValid())
        {
            $this->response->setStatus('400');

            return $this->jsonResponse([
                'errors'   => true,
                'messages' => $check->getErrors()
            ]);
        }

        if ($postData['email'] !== $user->email)
        {
            $user->generateActionToken();

            $user->save();

            return $this->jsonResponse([
                'message' => $this->i18n->get("tomos::auth.forgot.bademail")
            ]);
        }

        $user->setPassword($postData['password']);

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
