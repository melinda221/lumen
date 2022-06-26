<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Midtrans\Config;
use App\Http\Controllers\Midtrans\CoreApi;
use App\Models\Order;
use PDO;

class PaymentsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->request = $req;
    }

    public function buyProduct(Request $req)
    {
        try {
            $result = null;
            $payment_method = $req->payment_method;
            $transaction['transaction_details']['gross_amount']= $req->total_amount;
            $transaction['transaction_details']['order_id']= $req->order_id;
            $transaction['customer_details']['first_name']= 'SMARTPARKING' ;

            switch ($payment_method) {
                case 'qris':
                    $result = self::qris($transaction);
                    break;
                case 'credit_card':
                    $token_id = $req->token_id;
                    $result = self::chargeCreditCard($token_id, $transaction);
                    break;
            }
        return $result;
        } catch (\Exception $th) {
            dd($th);
            return ['code' => 0, 'message' => 'Terjadi kesalahan'];
        }
    }
   
    static function qris($transaction_object)
    {
        try {
            $transaction = $transaction_object;
            $transaction['payment_type'] = "qris";
            $transaction['qris'] = array(
                "enable_callback" => true,
                "callback_url" => "gojek://callback",
            );
        $charge = CoreApi::charge($transaction);
                    if (!$charge) {
                        return ['code' => 0, 'message' => 'Terjadi kesalahan'];
                    }
        $order = new Order();
                    $order->invoice = $transaction['transaction_details']['order_id'];
                    $order->transaction_id = $charge->transaction_id;
                    $order->gross_amount = $charge->gross_amount;
                    $order->status = "PENDING";
        if (!$order->save())
                        return false;
        return ['code' => 1, 'message' => 'Success', 'result' => $charge];
                } catch (\Exception $e) {
                    return ['code' => 0, 'message' => 'Terjadi kesalahan'];
                }
            }
            
}

