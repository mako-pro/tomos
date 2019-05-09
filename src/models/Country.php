<?php

namespace placer\tomos\models;

use mako\database\midgard\ORM;

class Country extends ORM
{
    protected $tableName = 'tomos_countries';

    /**
     * Users
     *
     * @return \mako\database\midgard\relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

}
