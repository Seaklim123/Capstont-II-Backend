<?php
namespace App\Repositories;

use App\Models\Products;
use App\Repositories\Interfaces\ProductRepositoriesInterfaces;
use Illuminate\Database\Eloquent\Collection;
use PhpParser\Node\Expr\Cast\Double;

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

//    public function getProductsMostPurchased(string $name, Double $totalPrice): Collection
//    {
////        $displayProductMostPurchased = Products::Where('name', 'LIKE', "%$name%")
////            ->join('order_informations')
//        return Products::all()->where('numberOrder', '>', $totalPrice);
//    }
}
