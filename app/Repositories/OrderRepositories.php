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

    public function acceptOrder(array $data, int $id)
{
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

        // Update main order
        $order->status = $data['status'];
        $order->save();

        // Update each orderList, but skip ones already cancelled
        foreach ($order->orderLists as $orderList) {
            if ($orderList->status === 'cancel') {
                // 🔸 Skip already-cancelled items
                continue;
            }

            // Update others to match main order status
            $orderList->status = $data['status'];
            $orderList->save();
        }

        return $order->load('orderLists');
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