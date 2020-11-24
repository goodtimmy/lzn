<?php

namespace App\Http\Controllers;

use App\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mail;
use App\GroupOrders;
use App\User;

class ReservationController extends Controller
{

    /**
     * Get available hours for date
     *
     * @return array
     */
    public function getHourList(Request $request)
    {
        $inputs = $request->all();

        $from = env('open_period_from', 11);
        $till = env('open_period_till', 21);
        $openHours = [];
        $return = [];
        for ($h = $from; $h <= $till; $h++) {
            $openHours[] = $h . ':00';
        }

        if (empty($inputs['b1'])) {
            $inputs['b1'] = 0;
        }
        if (empty($inputs['b2'])) {
            $inputs['b2'] = 0;
        }

        $nowWithPrepare = Carbon::now()->setTimezone('Europe/Prague')->addMinutes(env('preparation_time', 60));
        foreach ($openHours as $hour) {
            $allowHour = false;
            $startAt = Carbon::parse($inputs['date'] . ' ' . $hour);
            $freeSpace = Reservation::checkReservationsTime($startAt, $inputs['b1'], $inputs['b2']);
            $timeIsUp = $nowWithPrepare->greaterThan($startAt);
            if ($freeSpace && !$timeIsUp) {
                $allowHour = true;
            }
            $return[] = ['value' => $hour, 'enabled' => $allowHour];
        }
        if (count($return) < 1) {
            $return[] = ['value' => 'please select another day', 'enabled' => 'false'];
        }

        return $return;
    }

    /**
     * Get available hours for year
     *
     * @return array
     */
    public function getReservationsCount()
    {
        return Reservation::getReservationsCount();
    }

    public function placeGroupOrder(Request $request)
    {

        $inputs = $request->all();

        // validation
        $rule = [
//            'email' => 'required',
//            'phone' => 'required',
//            'date' => 'required',
//            'hour' => 'required',
//            'persons' => 'required',

        ];
        $validator = \Validator::make($inputs, $rule);
        if ($validator->fails()) {
            return [
                'status' => 'fail',
                'error' => $validator->messages()
            ];
        }

        // checking if user exists
        $userId = User::getUserIdByPhoneOrEmail($inputs['email'], $inputs['phone']);

        // if there is no such user
        if (!isset($userId->id) || !$userId->id) {
            $id = User::createUser($inputs['name'], $inputs['email'], $inputs['phone']);
        } else {
            $id = $userId->id;
            User::updateUserData($id, ['name' => $inputs['name']]);
        }

        $inputs['user_id'] = $id;

        $order['id'] = GroupOrders::createOrder($inputs);

        $inputs['start'] = $inputs['date']. " " . $inputs['hour'] . ":00";

        Mail::send('emails.newGroupRequestPlaced', $inputs, function($message) use ($inputs)
        {
            $message->from(getcong('site_email'))
                ->to(getcong('site_email'))
                ->subject(getcong('site_name').' ' . __('new group request') . ' ' . $inputs['name']);
        });

        $order['status'] = 'success';
        return $order;

    }


}
