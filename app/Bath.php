<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;



class Bath extends Model
{
    //use SoftDeletes;

    protected $table = 'baths';

    static public function getBathList()
    {
        return self::get();
    }

    static public function getRoomsWithBusyBaths($startAt)
    {
        $array =  DB::table('reservations')
            ->select('bath_id')
            ->where('start_at', $startAt)
            ->orderBy('bath_id')
            ->pluck('bath_id')
            ->toArray();

        return self::select('room_id')
            ->whereIn('id', $array)
            ->groupBy('room_id')
            ->pluck('room_id')
            ->toArray();
    }

}


