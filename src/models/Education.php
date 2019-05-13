<?php

namespace placer\tomos\models;

use mako\database\midgard\ORM;
use mako\database\midgard\traits\TimestampedTrait;

class Education extends ORM
{
    use TimestampedTrait;

    protected $tableName = 'tomos_educations';

    /**
     * Returns rows array by user id
     *
     * @param  int    $id User id
     * @return array
     */
    public static function getByUserId(int $id)
    {
        return self::where('user_id', '=', $id)
            ->orderBy('id', 'desc')
            ->all();
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
