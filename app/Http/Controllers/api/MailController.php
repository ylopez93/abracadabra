<?php

namespace App\Http\Controllers\api;

use App\Order;
use App\Mail\SendMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\SendMailMessenger;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{

    public function sendEmail(Order $order) {

        $title = 'Le ha sido asignada una orden';
        $customer_details = [
        'name' => $order->get('name'),
        'email' => $order->get('email'),
        ];
        $order_details = [
            'Codigo' => $order->get('code'),
            'Nombre' => $order->get('user_name'),
            'Teléfono' => $order->get('user_phone'),
            'Dirección' => $order->get('user_address'),
            'Hora de entrega desde' => $order->get('pickup_time_from'),
            'Hora de entrega hasta' => $order->get('pickup_time_to'),
            'Mensaje' => $order->get('message')
         ];

           $sendmail = Mail::to($customer_details['email'])
           ->send(new SendMail($title, $customer_details,$order_details));
           if (empty($sendmail)) {
             return response()->json(['message'
             => 'Mail Sent Sucssfully'], 200);
             }else{
                 return response()->json(['message' => 'Mail Sent fail'], 400);
                }
    }


    public function sendEmailMessenger(Request $request) {

        $order = Order::findOrFail($request['id']);
        $title = 'Le ha sido asignada una nueva orden';
        $customer_details = [
        'name' => $request->get('name'),
        'email' => $request->get('email')
        ];
        $order_details = [
            // 'Codigo' => $request->get('code'),
            // 'Nombre' => $request->get('user_name'),
            // 'Teléfono' => $request->get('user_phone'),
            // 'Dirección' => $request->get('user_address'),
            // 'Hora de entrega desde' => $request->get('pickup_time_from'),
            // 'Hora de entrega hasta' => $request->get('pickup_time_to'),
            // 'Mensaje' => $request->get('message')
        ];

           $sendmail = Mail::to($customer_details['email'])
           ->send(new SendMailMessenger($title, $customer_details,$order_details));
           if (empty($sendmail)) {
             return response()->json(['message'
             => 'Mail Sent Sucssfully'], 200);
             }else{
                 return response()->json(['message' => 'Mail Sent fail'], 400);
                }

    }

}