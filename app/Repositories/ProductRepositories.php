<?php
namespace App\Repositories;

use App\Models\Products;
use App\Repositories\Interfaces\ProductRepositoriesInterfaces;
class ProductRepositories implements ProductRepositoriesInterfaces{
public function all()
    {
        return Products::all();
    }

    public function find(int $id)
    {
        return Products::find($id);
    }

    public function create(array $data)
    {
        return Products::create($data);
    }

    public function update(int $id, array $data)
    {
        $product = Products::findOrFail($id);
        $product->update($data);
        return $product;
    }

    public function delete(int $id): bool
    {
        $product = Products::findOrFail($id);
        return $product->delete();
    }

    public function getBestSellers()
    {
        return Products::where('is_best_seller', true)->get();
    }

    public function getDiscounts()
    {
        return Products::where('discount', '>', 0)->get();
    }
}