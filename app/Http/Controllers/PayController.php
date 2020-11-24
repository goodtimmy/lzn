<?php

namespace App\Http\Controllers;

use Auth;
use App;
use App\User;
use App\GroupOrders;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Reservation;
use Carbon\Carbon;
use Mail;

use Granam\GpWebPay\Settings as GpSettings;
use Granam\GpWebPay\DigestSigner;
use Granam\GpWebPay\CardPayResponse;
use Granam\GpWebPay\Codes\CurrencyCodes;
use Alcohol\ISO4217 as IsoCurrencies;
use Granam\GpWebPay\CardPayRequestValues;
use Granam\GpWebPay\CardPayRequest;
use Granam\GpWebPay\Exceptions\GpWebPayErrorByCustomerResponse;
use Granam\GpWebPay\Exceptions\GpWebPayErrorResponse;
use Granam\GpWebPay\Exceptions\ResponseDigestCanNotBeVerified;
use Granam\GpWebPay\Exceptions\Exception as GpWebPayException;

class PayController extends Controller
{
    protected $digestSigner;
    protected $settings;

    public function __construct()
    {
        if (env('APP_DEBUG')) {
            $this->settings = GpSettings::createForTest(
                storage_path( env('GP_PRIVATE_KEY_TEST') ),
                env('GP_PRIVATE_KEY_PASS_TEST'),
                storage_path( env('GP_PUBLIC_KEY_TEST') ),
                env('GP_MERCHANT_NUMBER_TEST'),
                route('payResponse')
            // without explicit URL for response the current will be used - INCLUDING query string
            );
        } else {
            $this->settings = GpSettings::createForProduction(
                storage_path( env('GP_PRIVATE_KEY') ),
                env('GP_PRIVATE_KEY_PASS'),
                storage_path( env('GP_PUBLIC_KEY') ),
                env('GP_MERCHANT_NUMBER'),
                route('payResponse')
            // without explicit URL for response the current will be used - INCLUDING query string
            );
        }


        $this->digestSigner = new DigestSigner($this->settings);
    }
    public function request(Request $request)
    {
        $input = $request->all();
        return $this->requestProcessing($input);
    }

    public function requestProcessing($input)
    {
        // creating user
        $user = User::where('email', $input['email'])->first();
        if (empty($user)) {
            $user = new User;
            $user->name = $input['name'] . ' ' . $input['lastname'];
            $user->usertype = 'client';
            $user->password = bcrypt( str_random(12) );
            $user->phone = $input['phone'];
            $user->email = $input['email'];
            $user->save();
        }

        // creating order and get id for ordernumber
        if(isset($input['start_at'])) {
            $startAt = $input['start_at'];
        } else {
            $startAt = $input['date'] . ' ' . $input['hour'];
        }
        $startAt = Carbon::parse($startAt);

        if (Reservation::checkReservationsTime($startAt, $input['b1Conut'], $input['b2Conut'])) {
            $id = (new \App\Reservation)->createReservation([
                'start_at'    => $startAt,
                'cap_1_count' => $input['b1Conut'],
                'cap_2_count' => $input['b2Conut'],
                'user_id'     => $user->id,
                'comment'     => $input['message'],
            ]);
        } else {
            return [
                'status' => 'fail',
                'message' => __('there are not enough empty baths for this time'),
            ];
        }

        $fields = [
            'AMOUNT' => $input['amount'] ,
            'CURRENCY' => 203,
            'MERCHANTNUMBER' => env('GP_MERCHANT_NUMBER'),
            'OPERATION' => 'CREATE_ORDER',
            'ORDERNUMBER' => $id,
            'DEPOSITFLAG' => 1,
        ];

        $currencyCodes = new CurrencyCodes(new IsoCurrencies());
        try {
            $cardPayRequestValues = CardPayRequestValues::createFromArray($fields, $currencyCodes);
            $cardPayRequest = new CardPayRequest($cardPayRequestValues, $this->settings, $this->digestSigner);
        } catch (GpWebPayException $exception) {
            /* show an apology to the customer
             * like "we are sorry, our payment gateway is temporarily unavailable" and log it, solve it */
            exit();
        }

        print "<html><body><script>";
        print "var form = document.createElement('form');";
        print "document.body.appendChild(form);";
        print "form.method = 'post';";
        print "form.action = '" . $cardPayRequest->getRequestUrl() . "';";
        print "var fields = {";

        foreach ($cardPayRequest as $name => $value) {
            print  $name .": '". $value. "',";
        }

        print "};";
        print "for (var field in fields) {";
        print "var input = document.createElement('input');";
        print "input.type = 'hidden';";
        print "input.name = field;";
        print "input.value = fields[field];";
        print "form.appendChild(input);";
        print "}";
        print "form.submit();";

        print '</script></body></html>';

    }

    public function response(Request $request)
    {

        try {
            $response = CardPayResponse::createFromArray($request->all(), $this->settings, $this->digestSigner);
            $this->setOrderAsPaid($request->get('ORDERNUMBER'));
            return view('pages.payment_success');

        } catch(GpWebPayErrorByCustomerResponse $gpWebPayErrorByCustomerResponse) {
            // some pretty error box for customer information about HIS mistake like invalid card number
            /**
             * WARNING: do not rely blindly on this detection - for example if YOU (developer) are sending
             * card number in a hidden field, because the customer provided it to its account before and
             * does not need to enter it again, but the card number has been refused by GP WebPay,
             * you will show to the customer confusing message about an invalid card number,
             * although he does not enter it.
             * For full list of auto-detected customer
             * mistakes @see GpWebPayErrorByCustomerResponse::isErrorCausedByCustomer
             */
            $errorMessage = $gpWebPayErrorByCustomerResponse->getResultText();
            return view('pages.payment_fail', compact('errorMessage'));
        } catch(GpWebPayErrorResponse $gpWebPayErrorResponse) {
            /* GP WebPay refuses request by OUR (developer) mistake like duplicate order number
             * - show an apology to the customer and log this, solve this */
            $errorMessage = $gpWebPayErrorResponse->getResultText();
            return view('pages.payment_fail', compact('errorMessage'));

        } catch(ResponseDigestCanNotBeVerified $responseDigestCanNotBeVerified) {
            /* values in response have been changed(!),
             * show an apology (or a warning?) to the customer and probably log this for evidence */
            $errorMessage = 'The values in response have been changed';
            return view('pages.payment_fail', compact('errorMessage'));
        } catch(GpWebPayException $gpWebPayException) { // EVERY exception share this interface
            /* some generic error like processing error on GP WebPay server,
             * show an apology to the customer and log this, solve this */
            $errorMessage = 'General error';
            return view('pages.payment_fail', compact('errorMessage'));
        }
        /**
         * its OK, lets process $response->getParametersForDigest();
         * @see \Granam\GpWebPay\CardPayResponse::getParametersForDigest
         */
    }

    public function setOrderAsPaid($orderId)
    {
        Reservation::approveReservation($orderId, 0, 1);

        $reservation = Reservation::getReservationToEdit($orderId);

        $data = [
            'name' => $reservation->name,
            'email' => $reservation->email,
            'phone' => $reservation->phone,
            'text' => $reservation->comment,
            'start' => $reservation->start_at,
        ];
        Mail::send('emails.newReservationClient', $data, function($message) use ($data)
        {
            $message->from(getcong('site_email'))
                ->to($data['email'], $data['name'])
                ->subject(getcong('site_name').' ' . __('Reservation from') . ' ' . $data['name']);
        });
        Mail::send('emails.newReservation', $data, function($message) use ($data)
        {
            $message->from(getcong('site_email'))
                ->replyTo($data['email'])
                ->to(getcong('site_email'), getcong('site_name'))
                ->subject(getcong('site_name').' ' . __('Reservation from') . ' ' . $data['name']);
        });
    }

    public function payClientRequest($idHash)
    {
        if (isset($idHash)) {
            $reservation = Reservation::where('id_hash', $idHash)->first();
        }
        if(!isset($reservation)) {
            return redirect()->route('reservation');
        }
        $group = GroupOrders::where('reservation_id', $reservation->id)->first();
        $user = User::where('id', $reservation->user_id)->first();

        $reservation->baths_for_1 = Reservation::getNumberOfSingleBathsForReservationsList($reservation->id);
        $reservation->baths_for_2 = Reservation::getNumberOfDoubleBathsForReservationsList($reservation->id);
        $reservation->price =
            $reservation->baths_for_1 * env('b1_price', 1600) +
            $reservation->baths_for_2 * env('b2_price', 2200);

            $data = [
            'name'        => $user->name,
            'phone'       => $user->phone,
            'email'       => $user->email,
            'baths_for_1' => $reservation->baths_for_1,
            'baths_for_2' => $reservation->baths_for_2,
            'message'     => $group->message,
            'price'       => $reservation->price,
            'idHash'      => $reservation->id_hash,
        ];
        return view('pages.peymentClientRequest', $data);
    }

    public function payClientRequestProcess(Request $request)
    {
        $inputs = $request->all();
        if (isset($inputs['id_hash'])) {
            $reservation = Reservation::where('id_hash', $inputs['id_hash'])->first();
        }
        if(!isset($reservation)) {
            redirect('reservation');
        }

        $group = GroupOrders::where('reservation_id', $reservation->id)->first();
        $user = User::where('id', $reservation->user_id)->first();

        $reservation->baths_for_1 = Reservation::getNumberOfSingleBathsForReservationsList($reservation->id);
        $reservation->baths_for_2 = Reservation::getNumberOfDoubleBathsForReservationsList($reservation->id);
        $reservation->price =
            $reservation->baths_for_1 * env('b1_price', 1600) +
            $reservation->baths_for_2 * env('b2_price', 2200);

        $input = [
            'b1Conut' => $reservation->baths_for_1,
            'b2Conut' => $reservation->baths_for_2,
            'amount'   => $reservation->price,
            'message' => $group->message,
            'start_at'   => $reservation->start_at,
            'email'   => $user->email,
        ];

        return $this->requestProcessing($input);
    }

}


