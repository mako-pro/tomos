<?php

namespace placer\tomos\models;

use mako\utility\UUID;
use mako\database\midgard\ORM;
use mako\database\midgard\traits\TimestampedTrait;

class Profile extends ORM
{
    use TimestampedTrait;

    protected $tableName = 'tomos_profiles';

    protected $primaryKeyType = ORM::PRIMARY_KEY_TYPE_UUID;

    /**
     * Generates a primary key
     *
     * @return string
     */
    protected function generatePrimaryKey()
    {
        return UUID::v4();
    }

    /**
     * User
     *
     * @return \mako\database\midgard\relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
