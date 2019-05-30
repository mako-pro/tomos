<?php

namespace placer\tomos\models;

use mako\database\midgard\ORM;
use mako\database\midgard\traits\TimestampedTrait;

class Profile extends ORM
{
    use TimestampedTrait;

    protected $tableName = 'tomos_profiles';

    protected $primaryKeyType = ORM::PRIMARY_KEY_TYPE_CUSTOM;

    /**
     * Generates a primary key
     *
     * @return string
     */
    protected function generatePrimaryKey()
    {
        $hash = sha1(uniqid(true));
        $uuid = substr($hash, 0, 12);

        while ($this->get($uuid))
        {
            $uuid = substr(str_shuffle($hash), 0, 12);
        }

        return $uuid;
    }

    /**
     * Returns profile instance by user id
     *
     * @param  int    $id User id
     * @return self
     */
    public static function getByUserId(int $id)
    {
        return self::where('user_id', '=', $id)->first();
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
