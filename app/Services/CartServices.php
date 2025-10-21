<?php

namespace App\Services;

use App\Repositories\Interfaces\CartRepositoriesInterfaces;

class CartServices{
    protected $cartRepository;

    public function __construct(CartRepositoriesInterfaces $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function getAllCarts()
    {
        return $this->cartRepository->getAll();
    }

    public function findCart(int $id)
    {
        $carts = $this->cartRepository->findBytable($id);

        return $carts; // ✅ Return the full collection 
   }
    
    public function createCart(array $data)
    {
        return $this->cartRepository->create($data);
    }

    public function updateCart(int $id, array $data)
    {
        return $this->cartRepository->update($id, $data);
    }

    public function deleteCart(int $id)
    {
        return $this->cartRepository->delete($id);
    }
}