<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Midtrans\Config;
use App\Http\Controllers\Midtrans\CoreApi;
use App\Models\Order;

class NotificationController extends Controller
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


    public function post(Request $request)
    {
        try {
            $notification_body = json_decode($request->getContent(), true);
            $invoice = $notification_body['order_id'];
            $transaction_id = $notification_body['transaction_id'];
            $status_code = $notification_body['status_code'];
$order = Order::where('invoice', $invoice)->where('transaction_id', $transaction_id)->first();
            if (!$order)
                return ['code' => 0, 'message' => 'Terjadi kesalahan | Pembayaran tidak valid'];
switch ($status_code) {
                case '200':
                    $order->status = "SETTLEMENT";
                    break;
                case '201':
                    $order->status = "PENDING";
                    break;
                case '202':
                    $order->status = "CANCEL";
                    break;
            }
$order->save();
            return response('Ok', 200)->header('Content-Type', 'text/plain');
        } catch (\Exception $e) {
            return response('Error', 404)->header('Content-Type', 'text/plain');
        }
    }

    
}

