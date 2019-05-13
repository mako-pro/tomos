<?php

namespace placer\tomos\controllers\account;

use mako\http\routing\Controller;
use placer\tomos\models\Experience;
use placer\tomos\models\Education;
use placer\tomos\models\Location;
use placer\tomos\models\Country;
use placer\tomos\models\Profile;

class ProfileController extends Controller
{
    /**
     * Outputs the user profile page
     *
     * @return mixed
     */
    public function page()
    {
        if (! $user = $this->gatekeeper->getUser())
            return $this->redirectResponse('tomos.login.page');

        $id = $user->getId();

        return $this->view->render('tomos::account.profile', [
            'profile'     => Profile::getByUserId($id)->toArray(),
            'location'    => $location = Location::getByUserId($id)->toArray(),
            'country'     => Country::get($location['country_id'])->toArray(),
            'experiences' => Experience::getByUserId($id),
            'educations'  => Education::getByUserId($id),
            'user'        => $user->toArray(),
        ]);
    }

}
