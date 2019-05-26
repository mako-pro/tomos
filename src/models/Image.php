<?php

namespace placer\tomos\models;

use mako\database\midgard\ORM;
use mako\database\midgard\traits\TimestampedTrait;

class Image extends ORM
{
    use TimestampedTrait;

    protected $tableName = 'tomos_images';

    /**
     * Returns rows array by user id
     *
     * @param  int     $id User id
     * @param  int     $limit
     * @return array
     */
    public static function getByUserId(int $id, int $limit = 10)
    {
        return self::where('user_id', '=', $id)
            ->where('enabled', '>', 0)
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->all();
    }

    /**
     * Returns images with pagination by user id
     *
     * @param  int    $id User id
     * @return array
     */
    public static function paginateByUserId(int $id)
    {
        return self::where('user_id', '=', $id)
            ->orderBy('id', 'desc')
            ->paginate();
    }

    /**
     * Returns the image by id and user_id
     *
     * @param  int    $id       Image id
     * @param  int    $user_id  User id
     * @return Image
     */
    public static function getFirstByUserId($id, $user_id)
    {
         return self::where('id', '=', $id)
             ->where('user_id', '=', $user_id)
             ->first();
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
