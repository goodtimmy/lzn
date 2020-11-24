<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Room extends Model
{
//    use SoftDeletes;

    protected $table = 'rooms';

    static public function getRoomList()
    {
        return self::get();
    }

}


