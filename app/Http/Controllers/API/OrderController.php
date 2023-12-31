<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    function placeOrder(OrderRequest $request) : JsonResponse 
    {
        $data = $request->safe()->only(['pickup','delivery','price']);
        $metadata = [
            'weight' =>$request->safe()->only(['weight'])['weight']
        ];
        $data['metadata'] = collect($metadata);
        $data['user_id'] = auth()->user()->id;
        Order::create($data);
        return ResponseController::response(true, 'Order successful', Response::HTTP_CREATED);
    }
    
    function pendingOrders() : JsonResponse 
    {
        if (auth()->user()->role == 'user') {
            # code...
            $orders = Order::with('rider:id,first_name,last_name,phone_number', 'delivery:id,city','pickup:id,city')->where('user_id', auth()->user()->id)->where('status', 0)->get();    
            return ResponseController::response(true, $orders, Response::HTTP_OK);
        }
        else {

            $orders = Order::with('user:id,first_name,last_name,phone_number', 'delivery:id,city','pickup:id,city')->where('rider_id', auth()->user()->id)->where('status', 0)->get();    
            if (count($orders) < 1) {
                # code...
                $orders = Order::with('user:id,first_name,last_name,phone_number', 'delivery:id,city','pickup:id,city')->whereNull('rider_id')->where('status', 0)->get();    
            }
            return ResponseController::response(true, $orders, Response::HTTP_OK);
        }
    }
    
    function orderHistory() : JsonResponse 
    {
        if (auth()->user()->role == 'user') {
            # code...
            $orders = Order::with('rider:id,first_name,last_name,phone_number', 'delivery:id,city','pickup:id,city')->where('user_id', auth()->user()->id)->where('status', 1)->get();    
            return ResponseController::response(true, $orders, Response::HTTP_OK);
        }
        else {

            $orders = Order::with('user:id,first_name,last_name,phone_number', 'delivery:id,city','pickup:id,city')->where('rider_id', auth()->user()->id)->where('status', 1)->get();
            return ResponseController::response(true, $orders, Response::HTTP_OK);
        }
    }
    
    function pickupOrder(OrderRequest $request) : JsonResponse 
    {
        $order = Order::find($request->safe()->only(['id'])['id']);
        $rider_id = auth()->user()->id;
        if (! $order) {
            return ResponseController::response(false, 'Order not found', Response::HTTP_NOT_FOUND);
        }
        if ($order->rider_id && $order->rider_id !== $rider_id ) {
            return ResponseController::response(false, 'Order has been accepted by another rider', Response::HTTP_NOT_FOUND);
        }
        if ($order->rider_id && $order->rider_id === $rider_id ) {
            return ResponseController::response(false, 'You have already accepted this order', Response::HTTP_NOT_FOUND);
        }
        $order->update(['rider_id'=>$rider_id]);
        return ResponseController::response(true, 'Order Accepted', Response::HTTP_OK);
    }
    
    function deliverOrder(OrderRequest $request) : JsonResponse 
    {
        $order = Order::find($request->safe()->only(['id'])['id']);
        $rider_id = auth()->user()->id;
        if (! $order) {
            return ResponseController::response(false, 'Order not found', Response::HTTP_NOT_FOUND);
        }
        if ($order->rider_id && $order->rider_id !== $rider_id ) {
            return ResponseController::response(false, 'Order has been accepted by another rider', Response::HTTP_NOT_FOUND);
        }
        $order->update(['rider_accepted'=>true]);
        return ResponseController::response(true, 'Order delivered. Wait for customer to confirm', Response::HTTP_OK);
    }

    function acceptOrder(OrderRequest $request) : JsonResponse 
    {
        $order = Order::find($request->safe()->only(['id'])['id']);
        $user_id = auth()->user()->id;
        if (! $order) {
            return ResponseController::response(false, 'Order not found', Response::HTTP_NOT_FOUND);
        }
        if ($order->user_id !== $user_id ) {
            return ResponseController::response(false, 'Unauthorized', Response::HTTP_UNAUTHORIZED);
        }
        $order->update(['user_accepted'=>true, 'status' => true, 'rating'=> $request->all()['rating']]);
        return ResponseController::response(true, 'Order received. Thank you.', Response::HTTP_OK);
    }
    
    function riderRating() : JsonResponse 
    {
        $user_id = auth()->user()->id;
        $orders = Order::where('rider_id', $user_id)->where('status', 1);
        $data = [];
        $data['rating'] = $orders->avg('rating');
        $data['count'] = $orders->count();

        return ResponseController::response(true, $data, Response::HTTP_OK);
    }
}
