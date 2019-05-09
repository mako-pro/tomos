<?php

namespace placer\tomos\models;

use mako\database\midgard\ORM;

class Country extends ORM
{
    protected $tableName = 'tomos_countries';

    /**
     * Returns list cointries sorted by name
     *
     * @return array
     */
    public static function getList()
    {
        return self::select(['id', 'name'])
            ->orderBy('name', 'asc')
            ->all();
    }

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
