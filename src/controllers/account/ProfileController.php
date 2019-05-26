<?php

namespace placer\tomos\controllers\account;

use mako\http\routing\Controller;
use placer\tomos\models\Experience;
use placer\tomos\models\Education;
use placer\tomos\models\Location;
use placer\tomos\models\Country;
use placer\tomos\models\Profile;
use placer\tomos\models\Image;

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
            'images'      => $this->createImagesList($id),
            'user'        => $user->toArray(),
        ]);
    }

    /**
     * Create the list sorted by images orientation
     *
     * @param  integer $id User id
     * @return array
     */
    protected function createImagesList($id)
    {
        $images = Image::getByUserId($id);

        $land = [];
        $port = [];

        foreach ($images as $image)
        {
            if ($image->orient == 'land')
            {
                $land[] = $image;
            }
            elseif ($image->orient == 'port')
            {
                $port[] = $image;
            }
        }

        if (count($land) == count($images) || count($port) == count($images))
        {
            return $images;
        }


        if (count($land) % 2 !== 0)
        {
            array_pop($land);
        }

        return array_merge($land, $port);
    }

}
