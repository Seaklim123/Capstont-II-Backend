<?php

namespace App\Repositories\Interfaces;

interface ReportRepositoryInterface
{
    /**
     * Get total earnings (all time)
     * @return float
     */
    public function getTotalEarnings(): float;

    /**
     * Get total earnings by current month
     * @return float
     */
    public function getTotalEarningsByCurrentMonth(): float;

    /**
     * Get total earnings by specific month and year
     * @param int $month
     * @param int $year
     * @return float
     */
    public function getTotalEarningsByMonth(int $month, int $year): float;

    /**
     * Get total number of cashiers
     * @return int
     */
    public function getTotalCashiers(): int;

    /**
     * Get active cashiers count
     * @return int
     */
    public function getActiveCashiers(): int;

    /**
     * Get products with most order earnings
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getProductsMostOrderEarnings(int $limit): \Illuminate\Support\Collection;

    /**
     * Get earnings by month for all 12 months (bar chart data)
     * @param int|null $year
     * @return array
     */
    public function getEarningsByMonthBarChart(?int $year): array;

    /**
     * Get detailed dashboard report with everything
     * @return array
     */
    public function getDetailedDashboardReport(): array;

    /**
     * Get cashier performance report
     * @return \Illuminate\Support\Collection
     */
    public function getCashierPerformance(): \Illuminate\Support\Collection;

    /**
     * Get sales summary
     * @return array
     */
    public function getSalesSummary(): array;

    /**
     * Get product performance summary
     * @return array
     */
    public function getProductPerformance(): array;

    /**
     * Get category revenue breakdown
     * @return \Illuminate\Support\Collection
     */
    public function getCategoryRevenueBreakdown(): \Illuminate\Support\Collection;

    /**
     * Get daily earnings for current month
     * @return \Illuminate\Support\Collection
     */
    public function getDailyEarningsCurrentMonth(): \Illuminate\Support\Collection;

    /**
     * Get order status breakdown
     * @return array
     */
    public function getOrderStatusBreakdown(): array;

    /**
     * Get payment method breakdown
     * @return \Illuminate\Support\Collection
     */
    public function getPaymentMethodBreakdown(): \Illuminate\Support\Collection;

    /**
     * Get top customers by spending
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getTopCustomersBySpending(int $limit): \Illuminate\Support\Collection;

    /**
     * Get revenue comparison (current vs previous period)
     * @return array
     */
    public function getRevenueComparison(): array;
}
