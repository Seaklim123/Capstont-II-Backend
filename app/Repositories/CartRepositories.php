<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Repositories\Interfaces\CartRepositoriesInterfaces;

class CartRepositories implements CartRepositoriesInterfaces{
    protected $model;

    public function __construct(Cart $cart)
    {
        $this->model = $cart;
    }

    public function getAll()
    {
        return $this->model->with(['product', 'table'])->get();
    }

    public function findById(int $id)
    {
        return $this->model->with(['product', 'table'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $cart = $this->findById($id);
        $cart->update($data);
        return $cart;
    }

    public function delete(int $id)
    {
        $cart = $this->findById($id);
        return $cart->delete();
    }
}