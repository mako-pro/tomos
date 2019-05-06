<?php

namespace placer\tomos\models;

use mako\database\midgard\ORM;

class Activity extends ORM
{
    protected $tableName = 'tomos_activity';

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
