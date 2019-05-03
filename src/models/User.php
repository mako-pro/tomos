<?php

namespace placer\tomos\models;

use mako\gatekeeper\entities\user\User as MakoUser;

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
