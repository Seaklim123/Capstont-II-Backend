<?php

namespace App\Http\Controllers\backend\orders;

use App\Http\Requests\backend\orders\CreateOrderRequest;
use App\Http\Requests\backend\orders\UpdateStatusOrderRequest;
use App\Http\Resources\Backend\orders\CheackOrderResourcse;
use App\Http\Resources\Backend\orders\UpdateOrderResource;
use App\Models\OrderInformation;
use App\Models\OrderList;
use App\Services\OrderService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class OrderController extends Controller
{
    //
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_id' => 'required|integer',
            'payment' => 'required|in:card,cash',
            'phone_number' => 'nullable|string|regex:/^[0-9+\-\s]{8,15}$/'
        ]);
        // dd(vars: $validated);

        try {
            $order = $this->orderService->startOrder(
                $request->table_id,
                $request->payment,
                $request->phone_number
            );

            return response()->json([
                'message' => 'Order created successfully',
                'data' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 400);
        }
    }
    public function index()
    {
        $orders = $this->orderService->getOrder();

        return CheackOrderResourcse::collection($orders);

    }
    //  Select Order By ID of OrderInformation 
    public function getByStatus(UpdateStatusOrderRequest $request)
    {
        $validated = $request->validated();

        $orders = $this->orderService->getOrderByStatus($validated);

        return CheackOrderResourcse::collection($orders);
    }

    // Get Order by Ordernumber
    public function findByNumber($id){
        $orders = $this->orderService->getOrderBynumber($id);

        return $orders;
    }
    public function markAsDone(UpdateStatusOrderRequest $request, $id)
    {
        $validated = $request->validated();
        return $this->orderService->acceptOrder($validated, $id);
    }

    public function checkOrder($id){
        $cheack = $this->orderService->CheackOrder($id);
        return CheackOrderResourcse::collection($cheack);
    }

    public function cancelOrder($id)
    {
        // Find the order by ID
        $cancel = $this->orderService->cancelOrderList($id);

        return $cancel;
    }


}
