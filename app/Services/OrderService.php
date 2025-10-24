<?php

namespace App\Services;

use App\Models\OrderInformation;
use App\Models\OrderList;
use App\Models\Cart;
use App\Repositories\Interfaces\OrderRepositoriesInterfaces;
use Illuminate\Support\Facades\DB;

class OrderService
{

    protected $orderRepository;

    public function __construct(OrderRepositoriesInterfaces $orderRepository){
         $this->orderRepository = $orderRepository ;
    }
    public function startOrder(int $tableId, string $payment, string $phone_number)
    {
        return $this->orderRepository->startOrder($tableId, $payment, $phone_number);
    }

    public function CheackOrder(int $id){
        
        $order = $this->orderRepository->getOrderById($id);
        return $order;
    }
    
    public function getOrderBynumber(int $id){
        
        $order = $this->orderRepository->getOrderBynumber($id);
        return $order;
    }
    public function getOrderByStatus( $status){
        
        $order = $this->orderRepository->getOrderByStatus($status);
        return $order;
    }
    public function getOrder(){
        
        return $this->orderRepository->getOrder();
    }

    public function acceptOrder(array $data, int $id)
    {
        return $this->orderRepository->acceptOrder($data, $id);
    }


}
