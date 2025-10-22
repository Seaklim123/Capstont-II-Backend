<?php

namespace App\Services\Interface;

use App\Dtos\OrderListDto;
use App\Models\OrderList;
use Illuminate\Support\Collection;

interface OrderListServiceInterface
{
    public function getAllOrdersLists(): Collection;
    public function getOrderListById(int $id): ?OrderList;
    public function createOrderList(OrderListDto $orderListDto): OrderList;
    public function updateOrderList(OrderListDto $orderListDto, int $id): ?OrderList;
    public function deleteOrderListById(int $id): bool;


}
