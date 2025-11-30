<?php

namespace App\Repositories\implement;

use App\Models\Products;
use App\Models\Category;
use App\Models\OrderInformation;
use App\Repositories\Interfaces\DashboardRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardRepository implements DashboardRepositoryInterface
{
    /**
     * Get products with most orders
     *
     * @param int $limit
     * @param string|null $startDate
     * @param string|null $endDate
     * @return \Illuminate\Support\Collection
     */
    public function getProductsWithMostOrders(int $limit = 10, ?string $startDate = null, ?string $endDate = null)
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

    /**
     * Search categories by name
     *
     * @param string $searchTerm
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function searchCategories(string $searchTerm, int $limit = 10)
    {
        return Category::select(
            'categories.id',
            'categories.name',
            'categories.image_path',
            DB::raw('COUNT(DISTINCT products.id) as product_count')
        )
            ->leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->where('categories.name', 'LIKE', "%{$searchTerm}%")
            ->groupBy('categories.id', 'categories.name', 'categories.image_path')
            ->limit($limit)
            ->get();
    }

    /**
     * Search products by name
     *
     * @param string $searchTerm
     * @param int|null $categoryId
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function searchProductsByName(string $searchTerm, ?int $categoryId = null, int $limit = 20)
    {
        $query = Products::select(
            'products.*',
            'categories.name as category_name'
        )
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('products.name', 'LIKE', "%{$searchTerm}%");

        if ($categoryId) {
            $query->where('products.category_id', $categoryId);
        }

        return $query->orderBy('products.name')
            ->limit($limit)
            ->get();
    }

    /**
     * Get total earnings (all time)
     *
     * @return float
     */
    public function getTotalEarnings(): float
    {
        $result = OrderInformation::where('status', 'completed')
            ->where('payment', 'paid')
            ->sum(DB::raw('totalPrice - COALESCE(discount, 0) - COALESCE(refund, 0)'));

        return (float) ($result ?? 0);
    }

    /**
     * Get earnings by date
     *
     * @param string|null $date
     * @return float
     */
    public function getEarningsByDate(?string $date = null): float
    {
        $targetDate = $date ? Carbon::parse($date) : Carbon::today();

        $result = OrderInformation::where('status', 'completed')
            ->where('payment', 'paid')
            ->whereDate('created_at', $targetDate)
            ->sum(DB::raw('totalPrice - COALESCE(discount, 0) - COALESCE(refund, 0)'));

        return (float) ($result ?? 0);
    }

    /**
     * Get earnings by date range
     *
     * @param string $startDate
     * @param string $endDate
     * @return float
     */
    public function getEarningsByDateRange(string $startDate, string $endDate): float
    {
        $result = OrderInformation::where('status', 'completed')
            ->where('payment', 'paid')
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->sum(DB::raw('totalPrice - COALESCE(discount, 0) - COALESCE(refund, 0)'));

        return (float) ($result ?? 0);
    }

    /**
     * Get earnings by current month
     *
     * @return float
     */
    public function getEarningsByCurrentMonth(): float
    {
        $result = OrderInformation::where('status', 'completed')
            ->where('payment', 'paid')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum(DB::raw('totalPrice - COALESCE(discount, 0) - COALESCE(refund, 0)'));

        return (float) ($result ?? 0);
    }

    /**
     * Get total number of orders
     *
     * @param string|null $status
     * @return int
     */
    public function getTotalOrders(?string $status = null): int
    {
        $query = OrderInformation::query();

        if ($status) {
            $query->where('status', $status);
        }

        return $query->count();
    }

    /**
     * Get total cancelled orders
     *
     * @return int
     */
    public function getTotalCancelledOrders(): int
    {
        return OrderInformation::where('status', 'cancelled')->count();
    }

    /**
     * Get total completed orders
     *
     * @return int
     */
    public function getTotalCompletedOrders(): int
    {
        return OrderInformation::where('status', 'completed')->count();
    }

    /**
     * Get total pending orders
     *
     * @return int
     */
    public function getTotalPendingOrders(): int
    {
        return OrderInformation::where('status', 'pending')->count();
    }

    /**
     * Get orders by date
     *
     * @param string|null $date
     * @return int
     */
    public function getOrdersByDate(?string $date = null): int
    {
        $targetDate = $date ? Carbon::parse($date) : Carbon::today();

        return OrderInformation::whereDate('created_at', $targetDate)->count();
    }

    /**
     * Get daily earnings for a date range (for charts)
     *
     * @param string $startDate
     * @param string $endDate
     * @return \Illuminate\Support\Collection
     */
    public function getDailyEarningsChart(string $startDate, string $endDate)
    {
        return OrderInformation::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(totalPrice - COALESCE(discount, 0) - COALESCE(refund, 0)) as earnings'),
            DB::raw('COUNT(*) as order_count')
        )
            ->where('status', 'completed')
            ->where('payment', 'paid')
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
    }

    /**
     * Get category performance statistics
     *
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getCategoryPerformance(int $limit = 10)
    {
        return Category::select(
            'categories.id',
            'categories.name',
            DB::raw('COUNT(DISTINCT carts.id) as total_orders'),
            DB::raw('SUM(carts.quantity) as total_items_sold'),
            DB::raw('SUM(carts.quantity * products.price * (1 - COALESCE(products.discount, 0) / 100)) as total_revenue')
        )
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('carts', 'products.id', '=', 'carts.product_id')
            ->join('order_lists', 'carts.id', '=', 'order_lists.cart_id')
            ->where('order_lists.status', '!=', 'cancelled')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_revenue', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get average order value
     *
     * @return float
     */
    public function getAverageOrderValue(): float
    {
        $result = OrderInformation::where('status', 'completed')
            ->where('payment', 'paid')
            ->avg(DB::raw('totalPrice - COALESCE(discount, 0) - COALESCE(refund, 0)'));

        return (float) ($result ?? 0);
    }

    /**
     * Get total refunds
     *
     * @return float
     */
    public function getTotalRefunds(): float
    {
        $result = OrderInformation::sum('refund');

        return (float) ($result ?? 0);
    }

    /**
     * Get total discounts given
     *
     * @return float
     */
    public function getTotalDiscounts(): float
    {
        $result = OrderInformation::sum('discount');

        return (float) ($result ?? 0);
    }
}
