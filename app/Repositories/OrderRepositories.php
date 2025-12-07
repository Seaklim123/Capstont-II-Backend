<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\OrderInformation;
use App\Models\OrderList;
use App\Repositories\Interfaces\OrderRepositoriesInterfaces;
use DB;

class OrderRepositories implements OrderRepositoriesInterfaces{
    public function startOrder(int $tableId, string $payment, ?string $phone_number = null)
    {
        return DB::transaction(function () use ($tableId, $payment, $phone_number) {
            // 1️⃣ Fetch carts that are ready to order
            $carts = Cart::where('table_id', $tableId)
                ->where('status', 'starting')
                ->get();

            if ($carts->isEmpty()) {
                throw new \Exception('No items found in cart.');
            }

            // 2️⃣ Generate unique order number (better than just time())
            $numberOrder = now()->timestamp . rand(100, 999);

            // 3️⃣ Calculate total price
            $totalPrice = $carts->sum(function ($cart) {
                $product = $cart->product;
                $priceProduct = $product->price - ($product->discount ?? 0);
                return $priceProduct * $cart->quantity;
            });

            // 4️⃣ Create main order info
            $orderInfo = OrderInformation::create([
                'numberOrder'   => $numberOrder,
                'totalPrice'    => $totalPrice,
                'phone_number'  => $phone_number,
                'status'        => 'starting', // ✅ valid value for your DB
                'payment'       => $payment,
            ]);

            // 5️⃣ Create order list entries
            foreach ($carts as $cart) {
                OrderList::create([
                    'numberOrder' => $numberOrder,
                    'status'      => 'starting', // ✅ changed from 'starting'
                    'cart_id'     => $cart->id,
                ]);
            }

            // 6️⃣ Update cart status to mark them as ordered
            Cart::where('table_id', $tableId)->update(['status' => 'ordering']);

            return $orderInfo;
        });
    }

    public function getOrder(){
        $orders =  OrderInformation::with([
            'orderLists.cart.product',     // ✅ relationship, not column
            'orderLists.cart.tableNumber'  // ✅ relationship, not column
        ])->get();
        // $orders->map(function ($order) {
        //     $refundTotal = 0;

        //     foreach ($order->orderLists as $orderList) {
        //         if ($orderList->status === 'cancel') {
        //             $product = $orderList->cart->product;
        //             $refundTotal += ($product->price - $product->discount) * $orderList->cart->quantity;
        //         }
        //     }

        //     // Add refund attribute dynamically
        //     $order->refund = $refundTotal;
        //     $order->priceperorder = $order->totalPrice - $refundTotal;

        //     return $order;
        // });

        return $orders;
    }
    public function coutRefune($number){

    }
    public function getOrderById($id){
        $orders = OrderInformation::with([
            'orderLists.cart.product',     // ✅ relationship, not column
            'orderLists.cart.tableNumber'  // ✅ relationship, not column
        ])->where('id', $id)->get();
            //     $orders->map(function ($order) {
            //     $refundTotal = 0;

            //     foreach ($order->orderLists as $orderList) {
            //         if ($orderList->status === 'cancel') {
            //             $product = $orderList->cart->product;
            //             $refundTotal += ($product->price - $product->discount) * $orderList->cart->quantity;
            //         }
            //     }

            //     // Add refund attribute dynamically
            //     $order->refund = $refundTotal;
            //     $order->priceperorder = $order->totalPrice - $refundTotal;

            //     return $order;
            // });

        return $orders;
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
            // foreach ($order->orderLists as $orderList) {
            //     // Skip items already canceled
            //     if ($orderList->status === 'cancel') {
            //         // If cart exists, calculate refund
            //         if ($orderList->cart && $orderList->cart->product) {
            //             $price = (float) $orderList->cart->product->price;
            //             $quantity = (int) ($orderList->cart->quantity ?? 1);
            //             $refundTotal += $price * $quantity;
            //         }
            //         continue;
            //     }

            //     // Otherwise, update status normally
            //     $orderList->status = $data['status'];
            //     $orderList->save();
            // }

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
        $orders = OrderInformation::with([
            'orderLists.cart.product',     // ✅ relationship, not column
            'orderLists.cart.tableNumber'  // ✅ relationship, not column
        ])->where('order_informations.status', $status)->get();
        
        // Transform the data to include item details and refund calculations
        $orders->map(function ($order) {
            $refundTotal = 0;
            $itemList = [];

            foreach ($order->orderLists as $orderList) {
                if ($orderList->cart && $orderList->cart->product) {
                    $product = $orderList->cart->product;
                    $quantity = $orderList->cart->quantity;
                    $unitPrice = $product->price - ($product->discount ?? 0);
                    $totalPrice = $unitPrice * $quantity;
                    
                    // If item is cancelled, add to refund
                    if ($orderList->status === 'cancel') {
                        $refundTotal += $totalPrice;
                    }
                    
                    // Add item details to list
                    $itemList[] = [
                        'order_list_id' => $orderList->id,
                        'product_name' => $product->name,
                        'product_price' => $product->price,
                        'discount' => $product->discount ?? 0,
                        'unit_price' => $unitPrice,
                        'quantity' => $quantity,
                        'total_price' => $totalPrice,
                        'status' => $orderList->status,
                        'note' => $orderList->cart->note ?? '',
                        'table_number' => $orderList->cart->tableNumber->number_table ?? null
                    ];
                }
            }

            // Add calculated fields to order
            $order->refund = $refundTotal;
            $order->final_price = $order->totalPrice - $refundTotal;
            $order->item_list = $itemList;
            $order->total_items = count($itemList);

            return $order;
        });
        
        return $orders;
    }

    /**
     * Get detailed item list for a specific order
     */
    public function getOrderItemList($orderNumber){
        $order = OrderInformation::with([
            'orderLists.cart.product',
            'orderLists.cart.tableNumber'
        ])->where('numberOrder', $orderNumber)->first();

        if (!$order) {
            return null;
        }

        $itemList = [];
        $refundTotal = 0;

        foreach ($order->orderLists as $orderList) {
            if ($orderList->cart && $orderList->cart->product) {
                $product = $orderList->cart->product;
                $quantity = $orderList->cart->quantity;
                $unitPrice = $product->price - ($product->discount ?? 0);
                $totalPrice = $unitPrice * $quantity;
                
                if ($orderList->status === 'cancel') {
                    $refundTotal += $totalPrice;
                }
                
                $itemList[] = [
                    'order_list_id' => $orderList->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_description' => $product->description ?? '',
                    'product_image' => $product->image ?? '',
                    'original_price' => $product->price,
                    'discount' => $product->discount ?? 0,
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'total_price' => $totalPrice,
                    'status' => $orderList->status,
                    'note' => $orderList->cart->note ?? '',
                    'table_number' => $orderList->cart->tableNumber->number_table ?? null,
                    'created_at' => $orderList->created_at,
                    'updated_at' => $orderList->updated_at
                ];
            }
        }

        return [
            'order_info' => [
                'id' => $order->id,
                'number_order' => $order->numberOrder,
                'total_price' => $order->totalPrice,
                'refund' => $refundTotal,
                'final_price' => $order->totalPrice - $refundTotal,
                'status' => $order->status,
                'payment' => $order->payment,
                'phone_number' => $order->phone_number,
                'note' => $order->note
            ],
            'item_list' => $itemList,
            'summary' => [
                'total_items' => count($itemList),
                'total_amount' => $order->totalPrice,
                'refund_amount' => $refundTotal,
                'final_amount' => $order->totalPrice - $refundTotal
            ]
        ];
    }
}
