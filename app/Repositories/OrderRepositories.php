<?php 

namespace App\Repositories;

use App\Models\Cart;
use App\Models\OrderInformation;
use App\Models\OrderList;
use App\Repositories\Interfaces\OrderRepositoriesInterfaces;
use DB;

class OrderRepositories implements OrderRepositoriesInterfaces{
    public function startOrder(int $tableId, string $payment, string $phone_number )
    {
        return DB::transaction(function () use ($tableId, $payment, $phone_number) {
            $carts = Cart::where('table_id', $tableId)
                ->where('status', 'starting')
                ->get();

            if ($carts->isEmpty()) {
                throw new \Exception('No items found in cart.');
            }

            $numberOrder = time(); // can replace with custom generator later
            
            $totalPrice = $carts->sum(function ($cart) {
            $priceProduct = $cart->product->price - $cart->product->discount;
            return $priceProduct * $cart->quantity;
            });


            $orderInfo = OrderInformation::create([
                'numberOrder' => $numberOrder,
                'totalPrice' => $totalPrice,
                'phone_number' => $phone_number,
                'status' => 'starting',
                'payment' => $payment,
            ]);

            foreach ($carts as $cart) {
                OrderList::create([
                    'numberOrder' => $numberOrder,
                    'status' => 'starting',
                    'cart_id' => $cart->id,
                ]);
            }

            Cart::where('table_id', $tableId)->update(['status' => 'ordering']);

            return $orderInfo;
        });
    }
    public function getOrder(){
        return OrderInformation::with([
            'orderLists.cart.product',     // ✅ relationship, not column
            'orderLists.cart.tableNumber'  // ✅ relationship, not column
        ])->get();
    }
    public function coutRefune($number){
        
    }
    public function getOrderById($id){
        $order = OrderInformation::with([
            'orderLists.cart.product',     // ✅ relationship, not column
            'orderLists.cart.tableNumber'  // ✅ relationship, not column
        ])->where('id', $id)->get();

        return $order;
    }
    public function getOrderBynumber($id){
        $orders = OrderInformation::with([
            'orderLists.cart.product',     // ✅ relationship, not column
            'orderLists.cart.tableNumber'  // ✅ relationship, not column
        ])->where('numberOrder', $id)->get();
        $orders->map(function ($order) {
        $refundTotal = 0;

        foreach ($order->orderLists as $orderList) {
            if ($orderList->status === 'cancel') {
                $product = $orderList->cart->product;
                $refundTotal += ($product->price - $product->discount) * $orderList->cart->quantity;
            }
        }

        // Add refund attribute dynamically
        $order->refund = $refundTotal;
        $order->priceperorder = $order->totalPrice - $refundTotal;

        return $order;
    });

    return $orders;

    }

    public function acceptOrder(array $data, int $id)
        {
            return DB::transaction(function () use ($data, $id) {
                $order = OrderInformation::with([
                    'orderLists.cart.product', 
                    'orderLists.cart.tableNumber'
                ])->find($id);

                if (!$order) {
                    throw new \Exception('Order not found.');
                }

                // ✅ Validate status
                $validStatuses = ['pending', 'accepted', 'cancel'];
                if (!in_array($data['status'], $validStatuses)) {
                    throw new \Exception('Invalid status value.');
                }

                // ✅ Update main order status
                $order->status = $data['status'];
                $order->save();

                $refundTotal = 0;

                // ✅ Loop through all order lists
                foreach ($order->orderLists as $orderList) {
                    // Skip items already canceled
                    if ($orderList->status === 'cancel') {
                        // If cart exists, calculate refund
                        if ($orderList->cart && $orderList->cart->product) {
                            $price = (float) $orderList->cart->product->price;
                            $quantity = (int) ($orderList->cart->quantity ?? 1);
                            $refundTotal += $price * $quantity;
                        }
                        continue;
                    }

                    // Otherwise, update status normally
                    $orderList->status = $data['status'];
                    $orderList->save();
                }

                // ✅ Update refund amount in the main order
                $order->refund = $refundTotal;
                $order->save();

                // ✅ Reload full order info with relationships
                return $order->load([
                    'orderLists.cart.product',
                    'orderLists.cart.tableNumber'
                ]);
            });
        }

    public function cancelList($id){
            $order = OrderList::where('id', $id)->first();

            if (!$order) {
                return response()->json([
                    'message' => 'Order not found'
                ], 404);
            }

            // Update the status to 'cancel'
            $order->status = 'cancel';
            $order->save();

            return response()->json([
                'message' => 'Order has been cancelled successfully',
                'data' => $order
            ], 200);
        }

    public function getOrderByStatus($status){
        $order = OrderInformation::with([
            'orderLists.cart.product',     // ✅ relationship, not column
            'orderLists.cart.tableNumber'  // ✅ relationship, not column
        ])->where('order_informations.status', $status)->get();
        return $order;
    }  
}