<?php

namespace placer\tomos\models;

use mako\database\midgard\ORM;
use mako\database\midgard\traits\TimestampedTrait;

class Setting extends ORM
{
    use TimestampedTrait;

    protected $tableName = 'tomos_settings';

    /**
     * Returns setting instance by user id
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
