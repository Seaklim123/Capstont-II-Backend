<?php

namespace App\Repositories\implement;
use App\Models\User;
use App\Models\Products;
use App\Models\Category;
use App\Models\OrderInformation;
use App\Models\Cart;
use App\Repositories\Interfaces\ReportRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportRepository implements ReportRepositoryInterface
{
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
     * Get total earnings by current month
     *
     * @return float
     */
    public function getTotalEarningsByCurrentMonth(): float
    {
        $result = OrderInformation::where('status', 'completed')
            ->where('payment', 'paid')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum(DB::raw('totalPrice - COALESCE(discount, 0) - COALESCE(refund, 0)'));

        return (float) ($result ?? 0);
    }

    /**
     * Get total earnings by specific month and year
     *
     * @param int $month
     * @param int $year
     * @return float
     */
    public function getTotalEarningsByMonth(int $month, int $year): float
    {
        $result = OrderInformation::where('status', 'completed')
            ->where('payment', 'paid')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum(DB::raw('totalPrice - COALESCE(discount, 0) - COALESCE(refund, 0)'));

        return (float) ($result ?? 0);
    }

    /**
     * Get total number of cashiers
     *
     * @return int
     */
    public function getTotalCashiers(): int
    {
        return User::where('role', 'cashier')->count();
    }

    /**
     * Get active cashiers count
     *
     * @return int
     */
    public function getActiveCashiers(): int
    {
        return User::where('role', 'cashier')
            ->where('status', 'active')
            ->count();
    }

    /**
     * Get products with most order earnings
     *
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getProductsMostOrderEarnings(int $limit = 10): \Illuminate\Support\Collection
    {
        return Products::select(
            'products.id',
            'products.name',
            'products.image_path',
            'products.price',
            'products.discount',
            'categories.name as category_name',
            DB::raw('COUNT(carts.id) as total_orders'),
            DB::raw('SUM(carts.quantity) as total_quantity_sold'),
            DB::raw('SUM(carts.quantity * products.price) as gross_revenue'),
            DB::raw('SUM(carts.quantity * products.price * (1 - COALESCE(products.discount, 0) / 100)) as net_revenue'),
            DB::raw('SUM(carts.quantity * products.price * COALESCE(products.discount, 0) / 100) as total_discounts')
        )
            ->join('carts', 'products.id', '=', 'carts.product_id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('order_lists', 'carts.id', '=', 'order_lists.cart_id')
            ->where('order_lists.status', '!=', 'cancelled')
            ->groupBy(
                'products.id',
                'products.name',
                'products.image_path',
                'products.price',
                'products.discount',
                'categories.name'
            )
            ->orderBy('net_revenue', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get earnings by month for all 12 months (bar chart data)
     *
     * @param int|null $year
     * @return array
     */
    public function getEarningsByMonthBarChart(?int $year = null): array
    {
        $targetYear = $year ?? Carbon::now()->year;

        $monthlyData = OrderInformation::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(totalPrice - COALESCE(discount, 0) - COALESCE(refund, 0)) as earnings'),
            DB::raw('COUNT(*) as order_count')
        )
            ->where('status', 'completed')
            ->where('payment', 'paid')
            ->whereYear('created_at', $targetYear)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get()
            ->keyBy('month');

        // Create array for all 12 months with zero as default
        $chartData = [];
        $monthNames = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        for ($month = 1; $month <= 12; $month++) {
            $chartData[] = [
                'month' => $month,
                'month_name' => $monthNames[$month],
                'earnings' => isset($monthlyData[$month]) ? (float) $monthlyData[$month]->earnings : 0.0,
                'order_count' => isset($monthlyData[$month]) ? (int) $monthlyData[$month]->order_count : 0,
                'year' => $targetYear
            ];
        }

        return $chartData;
    }

    /**
     * Get cashier performance report
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCashierPerformance(): \Illuminate\Support\Collection
    {
        return User::select(
            'users.id',
            'users.username',
            'users.email',
            'users.primary_phone',
            'users.status',
            DB::raw('COUNT(DISTINCT order_informations.id) as total_orders'),
            DB::raw('SUM(order_informations.totalPrice - COALESCE(order_informations.discount, 0) - COALESCE(order_informations.refund, 0)) as total_sales'),
            DB::raw('AVG(order_informations.totalPrice - COALESCE(order_informations.discount, 0) - COALESCE(order_informations.refund, 0)) as average_sale')
        )
            ->leftJoin('order_informations', 'users.id', '=', 'order_informations.user_id')
            ->where('users.role', 'cashier')
            ->where('order_informations.status', 'completed')
            ->where('order_informations.payment', 'paid')
            ->groupBy('users.id', 'users.username', 'users.email', 'users.primary_phone', 'users.status')
            ->orderBy('total_sales', 'desc')
            ->get();
    }

    /**
     * Get sales summary
     *
     * @return array
     */
    public function getSalesSummary(): array
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        return [
            'today' => [
                'earnings' => (float) (OrderInformation::where('status', 'completed')
                    ->where('payment', 'paid')
                    ->whereDate('created_at', $today)
                    ->sum(DB::raw('totalPrice - COALESCE(discount, 0) - COALESCE(refund, 0)')) ?? 0),
                'orders' => OrderInformation::whereDate('created_at', $today)->count(),
                'completed' => OrderInformation::where('status', 'completed')->whereDate('created_at', $today)->count(),
            ],
            'this_month' => [
                'earnings' => $this->getTotalEarningsByCurrentMonth(),
                'orders' => OrderInformation::whereMonth('created_at', $thisMonth->month)
                    ->whereYear('created_at', $thisMonth->year)->count(),
                'completed' => OrderInformation::where('status', 'completed')
                    ->whereMonth('created_at', $thisMonth->month)
                    ->whereYear('created_at', $thisMonth->year)->count(),
            ],
            'last_month' => [
                'earnings' => (float) (OrderInformation::where('status', 'completed')
                    ->where('payment', 'paid')
                    ->whereMonth('created_at', $lastMonth->month)
                    ->whereYear('created_at', $lastMonth->year)
                    ->sum(DB::raw('totalPrice - COALESCE(discount, 0) - COALESCE(refund, 0)')) ?? 0),
                'orders' => OrderInformation::whereMonth('created_at', $lastMonth->month)
                    ->whereYear('created_at', $lastMonth->year)->count(),
            ],
            'all_time' => [
                'earnings' => $this->getTotalEarnings(),
                'orders' => OrderInformation::count(),
                'completed' => OrderInformation::where('status', 'completed')->count(),
                'cancelled' => OrderInformation::where('status', 'cancelled')->count(),
                'pending' => OrderInformation::where('status', 'pending')->count(),
            ]
        ];
    }

    /**
     * Get product performance summary
     *
     * @return array
     */
    public function getProductPerformance(): array
    {
        $totalProducts = Products::count();
        $activeProducts = Products::where('status', 'active')->count();

        $mostSoldProduct = Products::select(
            'products.id',
            'products.name',
            DB::raw('SUM(carts.quantity) as total_sold')
        )
            ->join('carts', 'products.id', '=', 'carts.product_id')
            ->join('order_lists', 'carts.id', '=', 'order_lists.cart_id')
            ->where('order_lists.status', '!=', 'cancelled')
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->first();

        $highestRevenueProduct = Products::select(
            'products.id',
            'products.name',
            DB::raw('SUM(carts.quantity * products.price * (1 - COALESCE(products.discount, 0) / 100)) as revenue')
        )
            ->join('carts', 'products.id', '=', 'carts.product_id')
            ->join('order_lists', 'carts.id', '=', 'order_lists.cart_id')
            ->where('order_lists.status', '!=', 'cancelled')
            ->groupBy('products.id', 'products.name')
            ->orderBy('revenue', 'desc')
            ->first();

        return [
            'total_products' => $totalProducts,
            'active_products' => $activeProducts,
            'inactive_products' => $totalProducts - $activeProducts,
            'most_sold_product' => $mostSoldProduct,
            'highest_revenue_product' => $highestRevenueProduct,
        ];
    }

    /**
     * Get category revenue breakdown
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCategoryRevenueBreakdown(): \Illuminate\Support\Collection
    {
        return Category::select(
            'categories.id',
            'categories.name',
            'categories.image_path',
            DB::raw('COUNT(DISTINCT products.id) as product_count'),
            DB::raw('COUNT(DISTINCT carts.id) as order_count'),
            DB::raw('SUM(carts.quantity) as items_sold'),
            DB::raw('SUM(carts.quantity * products.price * (1 - COALESCE(products.discount, 0) / 100)) as revenue'),
            DB::raw('ROUND((SUM(carts.quantity * products.price * (1 - COALESCE(products.discount, 0) / 100)) /
                (SELECT SUM(c.quantity * p.price * (1 - COALESCE(p.discount, 0) / 100))
                 FROM carts c
                 JOIN products p ON c.product_id = p.id
                 JOIN order_lists ol ON c.id = ol.cart_id
                 WHERE ol.status != "cancelled") * 100), 2) as revenue_percentage')
        )
            ->leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->leftJoin('carts', 'products.id', '=', 'carts.product_id')
            ->leftJoin('order_lists', 'carts.id', '=', 'order_lists.cart_id')
            ->where('order_lists.status', '!=', 'cancelled')
            ->groupBy('categories.id', 'categories.name', 'categories.image_path')
            ->orderBy('revenue', 'desc')
            ->get();
    }

    /**
     * Get daily earnings for current month
     *
     * @return \Illuminate\Support\Collection
     */
    public function getDailyEarningsCurrentMonth(): \Illuminate\Support\Collection
    {
        $now = Carbon::now();

        return OrderInformation::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('DAY(created_at) as day'),
            DB::raw('SUM(totalPrice - COALESCE(discount, 0) - COALESCE(refund, 0)) as earnings'),
            DB::raw('COUNT(*) as order_count')
        )
            ->where('status', 'completed')
            ->where('payment', 'paid')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->groupBy(DB::raw('DATE(created_at)'), DB::raw('DAY(created_at)'))
            ->orderBy('date')
            ->get();
    }

    /**
     * Get order status breakdown
     *
     * @return array
     */
    public function getOrderStatusBreakdown(): array
    {
        $total = OrderInformation::count();
        $completed = OrderInformation::where('status', 'completed')->count();
        $pending = OrderInformation::where('status', 'pending')->count();
        $cancelled = OrderInformation::where('status', 'cancelled')->count();

        return [
            'total' => $total,
            'completed' => $completed,
            'pending' => $pending,
            'cancelled' => $cancelled,
            'completion_rate' => $total > 0 ? round(($completed / $total) * 100, 2) : 0,
            'cancellation_rate' => $total > 0 ? round(($cancelled / $total) * 100, 2) : 0,
        ];
    }

    /**
     * Get payment method breakdown
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPaymentMethodBreakdown(): \Illuminate\Support\Collection
    {
        return OrderInformation::select(
            'payment',
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(totalPrice - COALESCE(discount, 0) - COALESCE(refund, 0)) as total_amount'),
            DB::raw('ROUND((COUNT(*) / (SELECT COUNT(*) FROM order_informations) * 100), 2) as percentage')
        )
            ->where('status', 'completed')
            ->groupBy('payment')
            ->orderBy('count', 'desc')
            ->get();
    }

    /**
     * Get top customers by spending
     *
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getTopCustomersBySpending(int $limit = 10): \Illuminate\Support\Collection
    {
        return OrderInformation::select(
            'phone_number',
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(totalPrice - COALESCE(discount, 0) - COALESCE(refund, 0)) as total_spent'),
            DB::raw('AVG(totalPrice - COALESCE(discount, 0) - COALESCE(refund, 0)) as average_order_value'),
            DB::raw('MAX(created_at) as last_order_date')
        )
            ->where('status', 'completed')
            ->where('payment', 'paid')
            ->whereNotNull('phone_number')
            ->groupBy('phone_number')
            ->orderBy('total_spent', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get revenue comparison (current vs previous period)
     *
     * @return array
     */
    public function getRevenueComparison(): array
    {
        $currentMonth = Carbon::now();
        $previousMonth = Carbon::now()->subMonth();

        $currentRevenue = $this->getTotalEarningsByCurrentMonth();
        $previousRevenue = $this->getTotalEarningsByMonth($previousMonth->month, $previousMonth->year);

        $difference = $currentRevenue - $previousRevenue;
        $percentageChange = $previousRevenue > 0
            ? round(($difference / $previousRevenue) * 100, 2)
            : 0;

        return [
            'current_month' => [
                'month' => $currentMonth->format('F Y'),
                'revenue' => $currentRevenue,
            ],
            'previous_month' => [
                'month' => $previousMonth->format('F Y'),
                'revenue' => $previousRevenue,
            ],
            'comparison' => [
                'difference' => $difference,
                'percentage_change' => $percentageChange,
                'trend' => $difference > 0 ? 'up' : ($difference < 0 ? 'down' : 'stable'),
            ]
        ];
    }

    /**
     * Get detailed dashboard report with everything
     *
     * @return array
     */
    public function getDetailedDashboardReport(): array
    {
        return [
            'overview' => [
                'total_earnings' => $this->getTotalEarnings(),
                'current_month_earnings' => $this->getTotalEarningsByCurrentMonth(),
                'total_cashiers' => $this->getTotalCashiers(),
                'active_cashiers' => $this->getActiveCashiers(),
            ],
            'sales_summary' => $this->getSalesSummary(),
            'order_status' => $this->getOrderStatusBreakdown(),
            'product_performance' => $this->getProductPerformance(),
            'top_products_by_earnings' => $this->getProductsMostOrderEarnings(10),
            'category_breakdown' => $this->getCategoryRevenueBreakdown(),
            'cashier_performance' => $this->getCashierPerformance(),
            'monthly_earnings_chart' => $this->getEarningsByMonthBarChart(),
            'daily_earnings_current_month' => $this->getDailyEarningsCurrentMonth(),
            'payment_methods' => $this->getPaymentMethodBreakdown(),
            'top_customers' => $this->getTopCustomersBySpending(10),
            'revenue_comparison' => $this->getRevenueComparison(),
            'generated_at' => Carbon::now()->toDateTimeString(),
        ];
    }
}
