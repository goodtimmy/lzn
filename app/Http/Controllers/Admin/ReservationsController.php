<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use Session;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;
use App\Reservation;
use App\GroupOrders;
use App\User;
use Mail;

class ReservationsController extends MainAdminController
{

    public function index()
    {
        return view('admin.pages.reservations');
    }

    public function byList()
    {
        return view('admin.pages.reservationsList');
    }


    /**
     * @param  Request $request
     * @return array
     */
    public function getReservations(Request $request)
    {
        $inputs = $request->all();
        $rule = [
            'date' => 'required',
            'hour' => 'required'
        ];
        $validator = \Validator::make($inputs, $rule);
        if ($validator->fails()) {
            return [
                'status' => 'fail',
                'error' => $validator->messages()
            ];
        }

        $date = Carbon::parse($inputs['date'] . ' ' . $inputs['hour'] . ':00');
        $reservations = Reservation::getReservationsForTime($date);

        // Deviding reservations by unsorted and already assined to a bath
        $list = ['sorted', 'unsorted'];
        foreach ($reservations as $reservation) {
            if ($reservation->bath_id !== null) {
                $list['sorted'][] = $reservation;
            } else {
                $list['unsorted'][] = $reservation;
            }
        }

        $list['unsorted_all'] = Reservation::getAllUnsorted();
        $list['status'] = 'success';
        return $list;
    }

    /**
     * @param  Request $request
     * @return array
     */
    public function getReservationsList(Request $request)
    {
        $inputs = $request->all();
        $numberOfItemsPerPage = $inputs['numberOfItemsPerPage'];
        $selectedPage = $inputs['selectedPage'];

        // counting all main reservations that have no parent id
        // int
        $count = Reservation::countParentReservationsForList();

        // number of pages
        if($count%$numberOfItemsPerPage == 0) {
            $numberOfPages = $count/$numberOfItemsPerPage;
        }
        else {
            $numberOfPages = intval($count/$numberOfItemsPerPage) +1;
        }

        // list of all main reservations data (chunked)
        $reservations = Reservation::getReservationsList($numberOfItemsPerPage);

        $list = ['reservation_list'];
        foreach ($reservations[$selectedPage -1] as $reservation) {

            //adding parent bath capacity
            $reservation['number_of_single_baths'] = Reservation::getNumberOfSingleBathsForReservationsList($reservation['id']);
            $reservation['number_of_double_baths'] = Reservation::getNumberOfDoubleBathsForReservationsList($reservation['id']);

            if ($reservation['bath_capacity'] == 1) {
                $reservation['number_of_single_baths'] +=1;
            }
            else if ($reservation['bath_capacity'] == 2) {
                $reservation['number_of_double_baths'] +=1;
            }

            // push combined data to the list
            $list['reservation_list'][] = $reservation;
        }

        $list['status'] = 'success';
        $list['pages'] = $numberOfPages;
        return $list;
    }

    public function getGroupList()
    {
        $groups = GroupOrders::getGroupOrderList(10);

        // Deviding reservations by unsorted and already assined to a bath

//        $list['unsorted_all'] = GroupOrders::getAllUnsorted();
        $groups['status'] = 'success';
        return $groups;
    }

    public function getGroupOrder(Request $request)
    {
        $inputs = $request->all();
        $id = $inputs['id'];

        $order = GroupOrders::getGroupOrder($id);

        $date = strtotime($order['start_at']);                                                     //  var_dump($date);
        $order['selectedHour'] = date('H:i',$date);

        $order['status'] = 'success';
        return $order;
    }


    /**
     * @param  Request $request
     * @return array
     */
    public function setReservation(Request $request)
    {
        $inputs = $request->all();

        $bath_id = 1;
        foreach ($inputs['baths'] as $bath) {

            if (!empty($bath)) {
                $reservationId = $bath[0]['order_id']; // var_dump($reservationId);
                $bathId = $bath_id;

                // moving the reservation using
                Reservation::moveSingleReservationToBath($reservationId, $bathId);

                if(!$bath[0]['parent_id'])
                {
                    Reservation::approveReservationsWhenParentMovedToBath($reservationId);
                }
            }
            $bath_id += 1;
        }
        foreach ($inputs['unsortedOrders'] as $unsortedOrder) {
            if (!empty($unsortedOrder['order_id'])) {
                $order_id = $unsortedOrder['order_id'];
                Reservation::moveSingleReservationFromBathToUnsorted($order_id);
            }
        }
        // TODO find moved reservation_id and new bath_id for moved reservation
        // TODO Should we move child reservations?

        return ['status' => 'success'];
    }

    /**
     * @param  Request $request
     * @return array
     */
    public function getReservationToEdit(Request $id)
    {
        $inputs = $id->all();

        $order = Reservation::getReservationToEdit($inputs['order_id']);

        $date = strtotime($order->start_at);                                                     //  var_dump($date);
        $dateTime = date('H:i',$date);


        $order->selectedHour = $dateTime;

        if(!$order->parent_id) {

            $order->valB1 = 0;
            $order->valB2 = 0;

            // parent reservation type
            if($order->bath_capacity == 1) {
                $order->valB1 += 1;
            }
            else if($order->bath_capacity == 2) {
                $order->valB2 += 1;
            }

            $parent_id = $order->id;

            $allChildBathCapacity = Reservation::getChildReservationsToEdit($parent_id);

            $order = [$order];

            $arrayCount = 1;
            foreach ($allChildBathCapacity as $res) {

                $order[$arrayCount] =  $res;
                $arrayCount +=1;

                if($res['bath_capacity'] == 1) {
                    $order[0]->valB1 += 1;
                }

                else if($res['bath_capacity'] == 2) {
                    $order[0]->valB2 += 1;
                }
            }

        } else {
            $order = [$order];
        }

        return $order;
    }

    public static function saveCreatedOrEditedReservation(Request $request)
    {

        $inputs = $request->all();
        $inputs = $inputs['order_array'];
        $params = [];

        $rule = [
            'start_at' => 'required',
            'selectedHour' => 'required'
        ];
        $validator = \Validator::make($inputs, $rule);
        if ($validator->fails()) {
            return [
                'status' => 'fail',
                'error' => $validator->messages()
            ];
        }

        // Getting date from date field and time from time field
        $inputs['start_at'] = Carbon::parse($inputs['start_at'])->toDateString() . " " . $inputs['selectedHour'];
        $date = Carbon::create($inputs['start_at']);

        // id = 0 means new reservation
        if ($inputs['id'] == 0) {

            //reservation data
            if(!isset($inputs['paid'])) {
                $inputs['paid'] = 0;
            }

            if(!isset($inputs['approved'])) {
                $inputs['approved'] = 0;
            }

            $valB1 = 0;
            if(isset($inputs['valB1'])) {
                $valB1 = $inputs['valB1'];
            }

            $valB2 = 0;
            if(isset($inputs['valB2'])) {
                $valB2 = $inputs['valB2'];
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

            $params = [
                'start_at'      => $date,
                'user_id'       => $id,
                'parent_id'     => null,
                'bath_capacity' => 1,
                'paid'          => $inputs['paid'],
                'approved'      => $inputs['approved'],
                'id_hash'       => str_random(40),
                'company_name'  => $inputs['companyName'] ?? null,
                'vat_number'    => $inputs['vat'] ?? null,

            ];

            $firstReservationToSave = 1;
            $params['bath_capacity'] = 1;

            for($i = 0; $i < $valB1; $i++) {
                $mainReservationIdTemp = Reservation::createSingleReservation($params);

                if($firstReservationToSave == 1) {
                    $firstReservationToSave = 0;
                    $params['parent_id'] = $mainReservationIdTemp;
                    $params['id_hash'] = null;
                }
            }
            $params['bath_capacity'] = 2;
            for($i = 0; $i < $valB2; $i++) {
                $mainReservationIdTemp = Reservation::createSingleReservation($params);

                if($firstReservationToSave == 1) {
                    $firstReservationToSave = 0;
                    $params['parent_id'] = $mainReservationIdTemp;
                    $params['id_hash'] = null;
                }
            }
            $inputs['reservation_id'] = $params['parent_id'];

        } else {
            $oldDate =  Carbon::create($inputs['start_at_old']);

            // Saving edited reservation
            // Date time modification
            $inputs['start_at'] = $date;

            // some bullshit - need to be remade
            if(isset($inputs['deleted_at'])) {
                $inputs['deleted_at'] = Carbon::create($inputs['deleted_at']);
            }

            // remove reserved bath if date or time are changed
            if($oldDate != $date) {
                $inputs['bath_id'] = null;

                Reservation::saveEditedReservation($inputs);
                Reservation::modifyEditedChildReservationBathId($inputs);
                Reservation::approveReservation($inputs['id'], $inputs['approved'], $inputs['paid']);
            }
            else {

                if($inputs['bath_id'] == "0") {
                    $inputs['bath_id']  = null;
                }

                Reservation::saveEditedReservation($inputs);
                Reservation::approveReservation($inputs['id'], $inputs['approved'], $inputs['paid']);
            }
            $inputs['reservation_id'] = $inputs['id'];
        }
        if ( isset($inputs['groupOrder']) ) {
            GroupOrders::setGroupOrderProccessed($inputs);

            if( isset($inputs['payment_type']) && $inputs['payment_type'] == 'card' ) {
                $reservation = Reservation::where('id', $inputs['reservation_id'])->first();
                $inputs['payment_url'] = route('payClientRequest', ['idHash' => $reservation->id_hash]);

                // changing language to user language to send email
                $userLang = GroupOrders::where('id', $inputs['groupOrder'])->first()['ui_lang'];
                $adminLang = \App::getLocale();
                \App::setLocale($userLang);

                Mail::send('emails.groupPayRequestClient', $inputs, function($message) use ($inputs)
                {
                    $message->from(getcong('site_email'))
                        ->to($inputs['email'], $inputs['name'])
                        ->subject(getcong('site_name').' ' . __('payment request for') . ' ' . $inputs['name']);
                });

                \App::setLocale($adminLang);

            }

        }

        return ['status' => 'success'];
    }

    public function deleteReservation(Request $request) {

        $inputs = $request->all();
        $id = $inputs['id'];

        $reservationToBeDeleted = Reservation::find($id);

        if(!$reservationToBeDeleted->parent_id) {

            $child = Reservation::where('parent_id', '=', $id)->get();

            foreach ($child as $item) {

                Reservation::where('id', '=', $item->id)->delete();
            }
        }

        $reservationToBeDeleted->delete();
    }

    /**
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function drop(Request $request)
    {
        return response()->json([$request->all()]);
    }

    public function getHourListAdmin(Request $request)
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
            $return[] = ['value' => 'pease select another day', 'enabled' => 'false'];
        }

        return $return;
    }
}
