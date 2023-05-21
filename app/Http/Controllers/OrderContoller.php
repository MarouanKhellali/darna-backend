<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderContoller extends Controller
{
    public function store(Request $request){
        Order::create([
            'sender_id' =>  $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'property_id' => $request->property_id
        ]);
 
        return response()->json(['res'=>"your order was sent successfully"]);
    }
    public function show(Request $request){
        $query = Order::query();

        if ($request->has('seller_id') ) {
            $query->where('receiver_id', $request->seller_id);
        }
        if ($request->has('client_id') ) {
            $query->where('sender_id', $request->client_id);
        }
  
        $orders = $query->join('users', function ($join) {
            $join->on('users.userid', '=', 'orders.sender_id')
                 ->orWhere('users.userid', '=', 'orders.receiver_id');
        });
        return response()->json($orders->get());
    }

    public function update(Request $request){
        
        $order = new Order();
        $order->where("receiver_id", $request->seller_id)
            ->update(['accepted' => $request->status]);
        return response()->json(["res" => "your order is updated now"]);
    }

   
}
