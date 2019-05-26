<?php

namespace placer\tomos\models;

use mako\database\midgard\ORM;
use mako\database\midgard\traits\TimestampedTrait;

class Article extends ORM
{
    use TimestampedTrait;

    protected $tableName = 'tomos_articles';

    /**
     * Saves the record to the database
     *
     * @return bool
     */
    public function save(): bool
    {
        if (! $this->isPersisted)
        {
            $hash = sha1(uniqid(true));

            $uuid = substr($hash, 0, 8);

            while ($this->where('uuid', '=', $uuid)->count() > 0)
            {
                $uuid = substr(str_shuffle($hash), 0, 8);
            }

            $this->setColumnValue('uuid', $uuid);
        }

        return parent::save();
    }

    /**
     * Find top level articles by user id with limit
     *
     * @param  int     $id User id
     * @param  int     $limit
     * @return array
     */
    public static function getByUserId(int $id, int $limit = 10)
    {
        return self::where('user_id', '=', $id)
            ->where('parent_id', '=', 0)
            ->where('enabled', '>', 0)
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->all();
    }

    /**
     * Find top level articles by user id with pagination
     *
     * @param  int    $id User id
     * @return array
     */
    public static function paginateByUserId(int $id)
    {
        return self::where('user_id', '=', $id)
            ->where('parent_id', '=', 0)
            ->orderBy('id', 'desc')
            ->paginate();
    }

    /**
     * Returns the article by id and user_id
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
