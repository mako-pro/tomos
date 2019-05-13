<?php

namespace placer\tomos\controllers\account;

use mako\utility\Str;
use mako\http\routing\Controller;
use placer\tomos\models\Experience;
use placer\tomos\models\Education;
use placer\tomos\models\Location;
use placer\tomos\models\Country;
use placer\tomos\models\Profile;
use placer\tomos\models\Setting;

class SettingsController extends Controller
{
    /**
     * Outputs the account settings page
     *
     * @return mixed
     */
    public function page()
    {
        if (! $user = $this->gatekeeper->getUser())
            return $this->redirectResponse('tomos.login.page');

        $id = $user->getId();

        return $this->view->render('tomos::account.settings', [
            'profile'     => Profile::getByUserId($id)->toArray(),
            'location'    => Location::getByUserId($id)->toArray(),
            'setting'     => Setting::getByUserId($id)->toArray(),
            'experiences' => Experience::getByUserId($id),
            'educations'  => Education::getByUserId($id),
            'countries'   => Country::getList(),
            'user'        => $user->toArray(),
        ]);
    }

    /**
     * Manage the account settings
     *
     * @return mixed
     */
    public function handler()
    {
        if (! $user = $this->gatekeeper->getUser())
           return $this->declineInvalidRequest();

        if (! $user->isActivated() || $user->isBanned())
            return $this->declineInvalidRequest();

        $post = $this->request->getPost();

        $formType = $post->get('form_type');

        $formRules = $this->config->get('tomos::rules.' . $formType);

        $validator = $this->validator->create($post->all(), $formRules);

        if ($formType == 'account_experience')
        {
            $validator->addRulesIf('exp_to', ['required', 'date("Y/m")'], function () use($post)
            {
                return $post->get('exp_current') === null;
            });
        }

        if ($formType == 'account_password' && $password = $post->get('old_password'))
        {
            if ($user->validatePassword($password) === false)
            {
                return $this->jsonResponse([
                    'messages' => ['old_password' => 'Invalid password']
                ]);
            }
        }

        if (! $validator->isValid())
        {
            return $this->jsonResponse([
                'messages' => $validator->getErrors()
            ]);
        }

        switch ($formType)
        {
            case 'account_profile':
                $profile = Profile::getByUserId($user->getId());
                $profile->first_name = $post->get('first_name');
                $profile->last_name  = $post->get('last_name');
                $profile->birthday   = $post->get('birthday');
                $profile->phone      = $post->get('phone');
                $profile->email      = $post->get('email');
                $profile->heading    = $post->get('heading');
                $profile->intro      = Str::nl2br($post->get('intro'));
                $profile->save();
                break;

            case 'account_location':
                $location = Location::getByUserId($user->getId());
                $location->geo_lat    = $post->get('geo_lat');
                $location->geo_lon    = $post->get('geo_lon');
                $location->country_id = $post->get('country_id');
                $location->city       = $post->get('city');
                $location->save();
                break;

            case 'account_experience':
                $experience = new Experience;
                $experience->user_id     = $user->getId();
                $experience->company     = $post->get('exp_company');
                $experience->position    = $post->get('exp_position');
                $experience->city        = $post->get('exp_city');
                $experience->description = Str::nl2br($post->get('exp_text'));
                $experience->is_current  = $post->get('exp_current') ?? '0';
                $experience->from_date   = $post->get('exp_from');
                $experience->to_date     = $post->get('exp_to');
                $experience->save();
                $periodRight = $experience->is_current == '0' ? $experience->to_date : 'Present';
                $responseData = [
                    'image'    => $this->urlBuilder->to('/placer/tomos/img/users/default/logo-exp' . rand(1,3) . '.png'),
                    'position' => $experience->position,
                    'company'  => $experience->company,
                    'period'   => $experience->from_date . ' - ' . $periodRight,
                    'city'     => $experience->city,
                ];
                return $this->jsonResponse(['success' => $responseData]);

           case 'account_education':
                $education = new Education;
                $education->user_id   = $user->getId();
                $education->school    = $post->get('edu_school');
                $education->degree    = $post->get('edu_degree');
                $education->sphere    = $post->get('edu_sphere');
                $education->city      = $post->get('edu_city');
                $education->from_date = $post->get('edu_from');
                $education->to_date   = $post->get('edu_to');
                $education->save();
                $responseData = [
                    'image'  => $this->urlBuilder->to('/placer/tomos/img/users/default/logo-edu' . rand(1,3) . '.png'),
                    'school' => $education->school,
                    'degree' => $education->degree,
                    'sphere' => $education->sphere,
                    'period' => $education->from_date . ' - ' . $education->to_date,
                    'city'   => $education->city,
                ];
                return $this->jsonResponse(['success' => $responseData]);

            case 'account_setting':
                $setting = Setting::getByUserId($user->getId());
                $setting->can_view         = $post->get('can_view');
                $setting->can_tag          = $post->get('can_tag');
                $setting->show_followers   = $post->get('show_followers') ?? '0';
                $setting->show_email       = $post->get('show_email') ?? '0';
                $setting->show_phone       = $post->get('show_phone') ?? '0';
                $setting->show_experiences = $post->get('show_experiences') ?? '0';
                $setting->show_educations  = $post->get('show_educations') ?? '0';
                $setting->show_age         = $post->get('show_age') ?? '0';
                $setting->allow_follow     = $post->get('allow_follow') ?? '0';
                $setting->save();
                break;

            case 'account_password':
                $user->setPassword($post->get('new_password'));
                $user->save();
                break;

            default:
                return $this->declineInvalidRequest();
        }

        return $this->jsonResponse(['success' => 'Successfull updated']);
    }

    /**
     * Declines the bad requests
     *
     * @return Response
     */
    protected function declineInvalidRequest()
    {
        $this->response->setStatus('403');
        $this->response->setBody('Forbidden 403!');
        return $this->response->send();
    }

}
