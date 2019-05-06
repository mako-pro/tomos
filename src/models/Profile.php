<?php

namespace placer\tomos\models;

use mako\database\midgard\ORM;
use mako\database\midgard\traits\TimestampedTrait;

class Profile extends ORM
{
    use TimestampedTrait;

    protected $tableName = 'tomos_profiles';

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
