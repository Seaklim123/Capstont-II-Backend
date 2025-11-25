<?php

namespace App\Repositories\Interfaces;

interface DashboardRepositoryInterface
{
    /**
     * Get products with most orders
     */
    public function getProductsWithMostOrders(int $limit, ?string $startDate, ?string $endDate);

    /**
     * Search categories by name
     */
    public function searchCategories(string $searchTerm, int $limit);

    /**
     * Search products by name
     */
    public function searchProductsByName(string $searchTerm, ?int $categoryId, int $limit);

    /**
     * Get total earnings
     * @return float
     */
    public function getTotalEarnings(): float;

    /**
     * Get earnings by date
     * @return float
     */
    public function getEarningsByDate(?string $date): float;

    /**
     * Get earnings by date range
     * @return float
     */
    public function getEarningsByDateRange(string $startDate, string $endDate): float;

    /**
     * Get earnings by current month
     * @return float
     */
    public function getEarningsByCurrentMonth(): float;

    /**
     * Get total orders
     * @return int
     */
    public function getTotalOrders(?string $status): int;

    /**
     * Get total cancelled orders
     * @return int
     */
    public function getTotalCancelledOrders(): int;

    /**
     * Get total completed orders
     * @return int
     */
    public function getTotalCompletedOrders(): int;

    /**
     * Get total pending orders
     * @return int
     */
    public function getTotalPendingOrders(): int;

    /**
     * Get orders by date
     * @return int
     */
    public function getOrdersByDate(?string $date): int;

    /**
     * Get daily earnings chart data
     */
    public function getDailyEarningsChart(string $startDate, string $endDate);

    /**
     * Get category performance
     */
    public function getCategoryPerformance(int $limit);

    /**
     * Get average order value
     */
    public function getAverageOrderValue();

    /**
     * Get total refunds
     */
    public function getTotalRefunds();

    /**
     * Get total discounts
     */
    public function getTotalDiscounts();
}
