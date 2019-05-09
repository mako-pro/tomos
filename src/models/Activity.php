<?php

namespace placer\tomos\models;

use mako\database\midgard\ORM;

class Activity extends ORM
{
    protected $tableName = 'tomos_activity';

    /**
     * Returns rows array by user id
     *
     * @param  int    $id User id
     * @return array
     */
    public static function getByUserId(int $id)
    {
        return self::where('user_id', '=', $id)->all();
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
