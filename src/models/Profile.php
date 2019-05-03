<?php

namespace tomos\models;

use mako\database\midgard\ORM;

class Profile extends ORM
{
    protected $tableName = 'profiles';

    /**
     * User
     *
     * @return \mako\database\midgard\relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Country
     *
     * @return \mako\database\midgard\relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

}
