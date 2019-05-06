<?php

namespace placer\tomos\models;

use mako\gatekeeper\entities\user\User as MakoUser;

/**
 * In file   app/config/gatekeeper.php
 * Replace  'user_model'  => 'mako\gatekeeper\entities\user\User'
 * To       'user_model'  => 'placer\tomos\models\User'
 */

class User extends MakoUser
{
    /**
     * User profile
     *
     * @return \mako\database\midgard\relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * User location
     *
     * @return \mako\database\midgard\relations\HasOne
     */
    public function location()
    {
        return $this->hasOne(Location::class);
    }

    /**
     * User account setting
     *
     * @return \mako\database\midgard\relations\HasOne
     */
    public function setting()
    {
        return $this->hasOne(Setting::class);
    }

    /**
     * User activity
     *
     * @return \mako\database\midgard\relations\HasMany
     */
    public function activity()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * User experience
     *
     * @return \mako\database\midgard\relations\HasMany
     */
    public function experience()
    {
        return $this->hasMany(Experience::class);
    }

    /**
     * User education
     *
     * @return \mako\database\midgard\relations\HasMany
     */
    public function education()
    {
        return $this->hasMany(Education::class);
    }

    /**
     * Search scope
     *
     * @param  \mako\database\query\Query $query
     * @param  string $s
     * @return \mako\database\query\Query
     */
    public function searchScope($query, $s)
    {
        return $query->where('username', 'LIKE', "%$s%")->orWhere('email', 'LIKE', "%$s%");
    }

}
