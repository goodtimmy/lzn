<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class Reservation extends Model
{
    use SoftDeletes;

    CONST MAX_COUNT_SINGLE_BATH = 12;
    CONST MAX_COUNT_DOUBLE_BATH = 4;

    protected $table = 'reservations';

    public function bath()
    {
        return $this->hasOne(Bath::class);
    }

    static public function getReservationsForTime($startAt)
    {

        $endAt = Carbon::instance($startAt)->addHours(env('reservation_period', 1));

        return self
            ::select('reservations.*', 'users.name')
            ->where('start_at', '<=', $startAt)
            ->where('end_at', '>=', $endAt)
            ->leftJoin('users', 'users.id', '=', 'reservations.user_id')
            ->get();
    }

    static public function getAllUnsorted()
    {
        return self
            ::WhereNull('bath_id')
            ->get()
            ->count();
    }

    // List of reservations
    static public function countParentReservationsForList()
    {
        return self
            ::WhereNull('parent_id')
            ->get()
            ->count();
    }

    static public function getReservationsList($parts)
    {

        return self
            ::select('reservations.*', 'users.name', 'users.email', 'users.phone')
            ->WhereNull('parent_id')
            ->leftJoin('users', 'users.id', '=', 'reservations.user_id')
            ->orderBy('reservations.id', 'desc')
            ->get()
            ->chunk($parts);
    }

    static public function getNumberOfSingleBathsForReservationsList($parent_id)
    {

        return self
            ::select('reservations.*')
            ->where('parent_id', '=', $parent_id)
            ->where('bath_capacity', '=', 1)
            ->get()
            ->count();

    }

    static public function getNumberOfDoubleBathsForReservationsList($parent_id)
    {

        return self
            ::select('reservations.*')
            ->where('parent_id', '=', $parent_id)
            ->where('bath_capacity', '=', 2)
            ->get()
            ->count();

    }

    static public function getReservationsCount()
    {
        $startAt = Carbon::now()->setTimezone('Europe/Prague');
        $endAt = Carbon::instance($startAt)->setTimezone('Europe/Prague')->addYear();

        $b1 = self
            ::select('reservations.start_at as d', DB::raw('count(*) as b'))
            ->where('start_at', '>=', $startAt)
            ->where('end_at', '<=', $endAt)
            ->where('paid', 1)
            ->where('bath_capacity', 1)
            ->groupBy('start_at')
            ->get();

        $b2 = self
            ::select('reservations.start_at as d', DB::raw('count(*) as c'))
            ->where('start_at', '>=', $startAt)
            ->where('end_at', '<=', $endAt)
            ->where('paid', 1)
            ->where('bath_capacity', 2)
            ->groupBy('start_at')
            ->get();

        $b2 = $b2->toArray();
        $b1 = $b1->toArray();

        $list = [
            'b1' => $b1,
            'b2' => $b2,
        ];

        return $list;
    }


    public static function getReservationsCountForTime($startAt)
    {
        $reservationsList = self::getReservationsForTime($startAt);
        $cap1FreeCount = 0;
        $cap2FreeCount = 0;
        foreach ($reservationsList as $reservation) {
            if ($reservation->paid) {
                if ($reservation->bath_capacity == 1) {
                    $cap1FreeCount++;
                } elseif ($reservation->bath_capacity == 2) {
                    $cap2FreeCount++;
                }
            }
        }

        return [
            'b1' => $cap1FreeCount,
            'b2' => $cap2FreeCount,
        ];
    }

    public static function getReservationToEdit($id)
    {
        return self
            ::select('users.id AS user_id', 'users.phone', 'users.email', 'users.name', 'reservations.*', 'baths.room_id')
            ->where('reservations.id', $id)
            ->leftJoin('users', 'users.id', '=', 'reservations.user_id')
            ->leftJoin('baths', 'reservations.bath_id', '=', 'baths.id')
            ->first();
    }

    public static function getChildReservationsToEdit($id)
    {
        return self
            ::select('reservations.id', 'reservations.bath_capacity', 'parent_id', 'users.name', 'reservations.company_name','reservations.vat_number')
            ->where('parent_id', $id)
            ->leftJoin('users', 'users.id', '=', 'reservations.user_id')
            ->get();
    }

    public static function saveEditedReservation($params)
    {
        $params['end_at'] = Carbon::instance($params['start_at'])
            ->addHours(env('reservation_period', 1));

        User::where('id', $params['user_id'])
            ->update([
                'name' => $params['name'],
                'email' => $params['email'],
                'phone' => $params['phone'],
            ]);

        self::where('id', $params['id'])
            ->update([
                'approved' => $params['approved'],
                'paid' => $params['paid'],
                'comment' => $params['comment'],
                'start_at' => $params['start_at'],
                'end_at' => $params['end_at'],
                'bath_id' => $params['bath_id'],
                'deleted_at' => $params['deleted_at'],
                'company_name'  => $params['companyName'] ?? null,
                'vat_number'    => $params['vat'] ?? null,
            ]);

        // update child reservation start date
        self::where('parent_id', $params['id'])
            ->update([
                'start_at' => $params['start_at'],
                'end_at' => $params['end_at'],
                'deleted_at' => $params['deleted_at']
            ]);

        return $params;
    }

    public static function modifyEditedChildReservationBathId($params)
    {
        // update child reservation start date
        self::where('parent_id', $params['id'])
            ->update([
                'bath_id' => $params['bath_id'],
            ]);

        return $params;
    }


    public static function checkReservationsTime($startAt, $cap1Count, $cap2Count)
    {
        $busy = self::getReservationsCountForTime($startAt);

        if ($cap1Count + $busy['b1'] > self::MAX_COUNT_SINGLE_BATH) {
            return false;
        }
        if ($cap2Count + $busy['b2'] > self::MAX_COUNT_DOUBLE_BATH) {
            return false;
        }

        return true;
    }

    public static function createSingleReservation($params)
    {
        $params['end_at'] = Carbon::instance($params['start_at'])
            ->addHours(env('reservation_period', 1));

        $result = self::insertGetId(
            [
                'start_at'      => $params['start_at'],
                'end_at'        => $params['end_at'],
                'user_id'       => $params['user_id'],
                'parent_id'     => $params['parent_id'] ?? null,
                'comment'       => $params['comment'] ?? null,
                'bath_capacity' => $params['bath_capacity'],
                'paid'          => $params['paid'] ?? 0,
                'approved'      => $params['approved'] ?? 0,
                "created_at"    => date('Y-m-d H:i:s'),
                'id_hash'       => $params['id_hash'] ?? null,
                'company_name'  => $params['company_name'] ?? null,
                'vat_number'    => $params['vat_number'] ?? null,
            ]
        );

        return $result;

    }

    public function createReservation($params)
    { //$startAt, $userId, $cap1Count, $cap2Count
        $params['end_at'] = Carbon::instance($params['start_at'])
            ->addHours(env('reservation_period', 1));
        $parent = null;

        $params['id_hash'] = str_random(40);
        $params['bath_capacity'] = 1;
        for ($i = 0; $i < $params['cap_1_count']; $i++) {
            $reservationId = self::createSingleReservation($params);
            if (empty($params['parent_id'])) {
                $params['parent_id'] = $reservationId;
                $params['id_hash'] = null;
            }
        }
        $params['bath_capacity'] = 2;
        for ($i = 0; $i < $params['cap_2_count']; $i++) {
            $reservationId = self::createSingleReservation($params);
            if (empty($params['parent_id'])) {
                $params['parent_id'] = $reservationId;
                $params['id_hash'] = null;
            }
        }
        return $params['parent_id'];
    }

    static public function approveReservation($id, $approved, $paid)
    {
        $reservation = self::where('id', $id)->first();
        $reservation->approved = $approved;
        $reservation->paid = $paid;
        $reservation->save();

        $chields = self::where('parent_id', $id)->get();
        foreach ($chields as $chield) {
            $chield->approved = $approved;
            $chield->paid = $paid;
            $chield->save();
        }

        return true;
    }

    static public function moveSingleReservationToBath($id, $bath_id)
    {
        $reservation = self::where('id', $id)->first();
        $reservation->bath_id = $bath_id;
        $reservation->save();

        return true;
    }

    static public function approveReservationsWhenParentMovedToBath($id)
    {
        $reservation = self::where('id', $id)->first();
        $reservation->approved = true;
        $reservation->save();

        $child = self::where('parent_id', $id)->get();
        foreach ($child as $child) {
            $child->approved = true;
            $child->save();
        }

        return true;
    }

    static public function moveSingleReservationFromBathToUnsorted($order_id)
    {
        $reservation = self::where('id', $order_id)->first();
        $reservation->bath_id = NULL;
        $reservation->save();
        return true;
    }
}


