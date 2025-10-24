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

            $totalPrice = $carts->sum(fn($cart) => $cart->product->price * $cart->quantity);

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
    public function getOrderById($id){
        $order = OrderInformation::with([
            'orderLists.cart.product',     // ✅ relationship, not column
            'orderLists.cart.tableNumber'  // ✅ relationship, not column
        ])->where('id', $id)->get();

        return $order;
    }
    public function getOrderBynumber($id){
        $order = OrderInformation::with([
            'orderLists.cart.product',     // ✅ relationship, not column
            'orderLists.cart.tableNumber'  // ✅ relationship, not column
        ])->where('numberOrder', $id)->get();
        return $order;
    }

    public function acceptOrder(array $data, int $id){
        return DB::transaction(function () use ($data, $id) {
        $order = OrderInformation::with('orderLists')->find($id);

        if (!$order) {
            throw new \Exception('Order not found.');
        }

        // Validate and update order status
        $validStatuses = ['pending', 'accepted', 'cancel'];

        if (!in_array($data['status'], $validStatuses)) {
            throw new \Exception('Invalid status value.');
        }

        $order->status = $data['status'];
        $order->save();

        // Update all related order lists to match the new status
        foreach ($order->orderLists as $orderList) {
            $orderList->status = $data['status']; // ✅ Now dynamic
            $orderList->save();
        }

        // Optionally, update carts back to "completed" if order done
        // if ($data['status'] === 'completed') {
        //     $tableId = $order->orderLists->first()?->table_id;
        //     if ($tableId) {
        //         Cart::where('table_id', $tableId)
        //             ->update(['status' => 'completed']);
        //     }
        // }

        return $order->load('orderLists');
    });
    }

    public function getOrderByStatus($status){
        $order = OrderInformation::with([
            'orderLists.cart.product',     // ✅ relationship, not column
            'orderLists.cart.tableNumber'  // ✅ relationship, not column
        ])->where('order_informations.status', $status)->get();
        return $order;
    }


    
}