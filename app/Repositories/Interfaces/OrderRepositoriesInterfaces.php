<?php 

namespace App\Repositories\Interfaces;

interface OrderRepositoriesInterfaces{
        public function startOrder(int $tableId, string $payment, string $phone_number);
        public function getOrderById($id);
        public function getOrderByStatus($status);
        public function getOrderBynumber($id);
        public function getOrder();
        public function acceptOrder(array $data, int $id);
} 