<?php
namespace App\Repositories;

use App\Models\Products;
use App\Repositories\Interfaces\ProductRepositoriesInterfaces;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
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


    public function getProductsWithMostOrders(int $limit, ?string $startDate, ?string $endDate)
    {
        $query = Products::select(
            'products.id',
            'products.name',
            'products.image_path',
            'products.price',
            'products.discount',
            'categories.name as category_name',
            DB::raw('COUNT(carts.id) as total_orders'),
            DB::raw('SUM(carts.quantity) as total_quantity'),
            DB::raw('SUM(carts.quantity * products.price * (1 - COALESCE(products.discount, 0) / 100)) as total_revenue')
        )
            ->join('carts', 'products.id', '=', 'carts.product_id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('order_lists', 'carts.id', '=', 'order_lists.cart_id')
            ->where('order_lists.status', '!=', 'cancelled');

        if ($startDate) {
            $query->where('carts.created_at', '>=', Carbon::parse($startDate)->startOfDay());
        }

        if ($endDate) {
            $query->where('carts.created_at', '<=', Carbon::parse($endDate)->endOfDay());
        }

        return $query->groupBy(
            'products.id',
            'products.name',
            'products.image_path',
            'products.price',
            'products.discount',
            'categories.name'
        )
            ->orderBy('total_orders', 'desc')
            ->limit($limit)
            ->get();
    }

}
