<?php

namespace App\Services\implementation;

use App\Repositories\Interfaces\DashboardRepositoryInterface;
use App\Services\Interface\DashboardServiceInterface;

class DashboardService implements DashboardServiceInterface
{
    protected DashboardRepositoryInterface $dashboardRepository;

    public function __construct(DashboardRepositoryInterface $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

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
        return $this->dashboardRepository->getProductsWithMostOrders($limit, $startDate, $endDate);
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
        return $this->dashboardRepository->searchCategories($searchTerm, $limit);
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
        return $this->dashboardRepository->searchProductsByName($searchTerm, $categoryId, $limit);
    }

    /**
     * Get total earnings (all time)
     *
     * @return float
     */
    public function getTotalEarnings(): float
    {
        return $this->dashboardRepository->getTotalEarnings();
    }

    /**
     * Get earnings by current date
     *
     * @param string|null $date
     * @return float
     */
    public function getEarningsByDate(?string $date = null): float
    {
        return $this->dashboardRepository->getEarningsByDate($date);
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
        return $this->dashboardRepository->getEarningsByDateRange($startDate, $endDate);
    }

    /**
     * Get earnings by current month
     *
     * @return float
     */
    public function getEarningsByCurrentMonth(): float
    {
        return $this->dashboardRepository->getEarningsByCurrentMonth();
    }

    /**
     * Get total number of orders
     *
     * @param string|null $status
     * @return int
     */
    public function getTotalOrders(?string $status = null): int
    {
        return $this->dashboardRepository->getTotalOrders($status);
    }

    /**
     * Get total cancelled orders
     *
     * @return int
     */
    public function getTotalCancelledOrders(): int
    {
        return $this->dashboardRepository->getTotalCancelledOrders();
    }

    /**
     * Get total completed orders
     *
     * @return int
     */
    public function getTotalCompletedOrders(): int
    {
        return $this->dashboardRepository->getTotalCompletedOrders();
    }

    /**
     * Get total pending orders
     *
     * @return int
     */
    public function getTotalPendingOrders(): int
    {
        return $this->dashboardRepository->getTotalPendingOrders();
    }

    /**
     * Get orders by current date
     *
     * @param string|null $date
     * @return int
     */
    public function getOrdersByDate(?string $date = null): int
    {
        return $this->dashboardRepository->getOrdersByDate($date);
    }

    /**
     * Get comprehensive dashboard statistics
     *
     * @return array
     */
    public function getDashboardStats(): array
    {
        return [
            'earnings' => [
                'total' => $this->getTotalEarnings(),
                'today' => $this->getEarningsByDate(),
                'this_month' => $this->getEarningsByCurrentMonth(),
            ],
            'orders' => [
                'total' => $this->getTotalOrders(),
                'today' => $this->getOrdersByDate(),
                'completed' => $this->getTotalCompletedOrders(),
                'pending' => $this->getTotalPendingOrders(),
                'cancelled' => $this->getTotalCancelledOrders(),
            ],
            'top_products' => $this->getProductsWithMostOrders(5),
            'financial' => [
                'total_refunds' => $this->getTotalRefunds(),
                'total_discounts' => $this->getTotalDiscounts(),
                'average_order_value' => $this->getAverageOrderValue(),
            ]
        ];
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
        return $this->dashboardRepository->getDailyEarningsChart($startDate, $endDate);
    }

    /**
     * Get category performance statistics
     *
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getCategoryPerformance(int $limit = 10)
    {
        return $this->dashboardRepository->getCategoryPerformance($limit);
    }

    /**
     * Get average order value
     *
     * @return float
     */
    public function getAverageOrderValue(): float
    {
        return $this->dashboardRepository->getAverageOrderValue();
    }

    /**
     * Get total refunds
     *
     * @return float
     */
    public function getTotalRefunds(): float
    {
        return $this->dashboardRepository->getTotalRefunds();
    }

    /**
     * Get total discounts given
     *
     * @return float
     */
    public function getTotalDiscounts(): float
    {
        return $this->dashboardRepository->getTotalDiscounts();
    }

    /**
     * Get financial summary
     *
     * @return array
     */
    public function getFinancialSummary(): array
    {
        $totalEarnings = $this->getTotalEarnings();
        $totalRefunds = $this->getTotalRefunds();

        return [
            'total_earnings' => $totalEarnings,
            'total_refunds' => $totalRefunds,
            'total_discounts' => $this->getTotalDiscounts(),
            'average_order_value' => $this->getAverageOrderValue(),
            'net_earnings' => $totalEarnings - $totalRefunds,
        ];
    }
}
