<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;


class GroupOrders extends Model
{
    use SoftDeletes;

    protected $table = 'group_orders';

    public static function createOrder($params)
    {
        $date = $params['date']. " " . $params['hour'] . ":00";

        $params['start_at'] = Carbon::createFromFormat('d.m.Y H:i:s', $date)->toDateTimeString();

        $date2 = Carbon::createFromFormat('d.m.Y H:i:s', $date);

        $params['end_at'] = Carbon::instance($date2)
            ->addHours(env('reservation_period', 1))->toDateTimeString();

        $result = self::insertGetId(
            [
                'user_id'      => $params['user_id'],
                'person_count' => $params['persons'],
                'start_at'     => $params['start_at'],
                'end_at'       => $params['end_at'],
                'message'      => $params['message'] ?? null,
                'ui_lang'      => app()->getLocale(),
            ]
        );

        return $result;

    }

    static public function getGroupOrderList($parts)
    {
        return self
            ::select('group_orders.*', 'users.name', 'users.email', 'users.phone')
            ->leftJoin('users', 'users.id', '=', 'group_orders.user_id')
            ->orderBy('group_orders.id', 'desc')
            ->get()
            ->chunk($parts);
    }



    static public function getGroupOrder($id)
    {
        return self
            ::select('group_orders.*', 'users.name', 'users.email', 'users.phone')
            ->where('group_orders.id', '=', $id)
            ->leftJoin('users', 'users.id', '=', 'group_orders.user_id')
            ->orderBy('group_orders.id', 'desc')
            ->first();
    }

    static public function getAllUnsorted() {

        return self
//            ::where('start_at', '>=', $startAt)
            ::WhereNull('processed')
            ->get()
            ->count();
    }

    public static function setGroupOrderProccessed($params)
    {
        $id = $params['groupOrder'];
        // update child reservation start date
        self::where('id', $id)
            ->update([
                'payment_type'   => $params['payment_type'] ?? null,
                'reservation_id' => $params['reservation_id'],
                'processed'      => 1,
            ]);

        return $id;
    }

}