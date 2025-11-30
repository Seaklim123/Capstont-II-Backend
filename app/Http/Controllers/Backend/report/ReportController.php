<?php

namespace App\Http\Controllers\Backend\report;

use App\Http\Controllers\Controller;
use App\Services\Interface\ReportServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    protected ReportServiceInterface $reportService;

    public function __construct(ReportServiceInterface $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Get total earnings (all time)
     *
     * @return JsonResponse
     */
    public function getTotalEarnings(): JsonResponse
    {
        try {
            $earnings = $this->reportService->getTotalEarnings();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_earnings' => $earnings
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Get total earnings error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve total earnings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get total earnings by current month
     *
     * @return JsonResponse
     */
    public function getCurrentMonthEarnings(): JsonResponse
    {
        try {
            $earnings = $this->reportService->getTotalEarningsByCurrentMonth();

            return response()->json([
                'success' => true,
                'data' => [
                    'current_month_earnings' => $earnings,
                    'month' => now()->format('F Y')
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Get current month earnings error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve current month earnings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get total cashiers
     *
     * @return JsonResponse
     */
    public function getTotalCashiers(): JsonResponse
    {
        try {
            $total = $this->reportService->getTotalCashiers();
            $active = $this->reportService->getActiveCashiers();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_cashiers' => $total,
                    'active_cashiers' => $active,
                    'inactive_cashiers' => $total - $active
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Get total cashiers error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve cashier information',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get products with most order earnings
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getProductsMostOrderEarnings(Request $request): JsonResponse
    {
        try {
            $limit = $request->input('limit', 10);
            $products = $this->reportService->getProductsMostOrderEarnings($limit);

            return response()->json([
                'success' => true,
                'data' => $products
            ], 200);
        } catch (\Exception $e) {
            Log::error('Get products most order earnings error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve product earnings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get earnings by month bar chart data
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getMonthlyEarningsChart(Request $request): JsonResponse
    {
        try {
            $year = $request->input('year', now()->year);
            $chartData = $this->reportService->getEarningsByMonthBarChart($year);

            return response()->json([
                'success' => true,
                'data' => [
                    'year' => $year,
                    'monthly_data' => $chartData
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Get monthly earnings chart error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve monthly earnings chart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get detailed dashboard report with everything
     *
     * @return JsonResponse
     */
    public function getDetailedReport(): JsonResponse
    {
        try {
            $report = $this->reportService->getDetailedDashboardReport();

            return response()->json([
                'success' => true,
                'data' => $report
            ], 200);
        } catch (\Exception $e) {
            Log::error('Get detailed report error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve detailed report',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cashier performance report
     *
     * @return JsonResponse
     */
    public function getCashierPerformance(): JsonResponse
    {
        try {
            $performance = $this->reportService->getCashierPerformance();

            return response()->json([
                'success' => true,
                'data' => $performance
            ], 200);
        } catch (\Exception $e) {
            Log::error('Get cashier performance error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve cashier performance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sales summary
     *
     * @return JsonResponse
     */
    public function getSalesSummary(): JsonResponse
    {
        try {
            $summary = $this->reportService->getSalesSummary();

            return response()->json([
                'success' => true,
                'data' => $summary
            ], 200);
        } catch (\Exception $e) {
            Log::error('Get sales summary error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve sales summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product performance
     *
     * @return JsonResponse
     */
    public function getProductPerformance(): JsonResponse
    {
        try {
            $performance = $this->reportService->getProductPerformance();

            return response()->json([
                'success' => true,
                'data' => $performance
            ], 200);
        } catch (\Exception $e) {
            Log::error('Get product performance error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve product performance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get category revenue breakdown
     *
     * @return JsonResponse
     */
    public function getCategoryRevenue(): JsonResponse
    {
        try {
            $breakdown = $this->reportService->getCategoryRevenueBreakdown();

            return response()->json([
                'success' => true,
                'data' => $breakdown
            ], 200);
        } catch (\Exception $e) {
            Log::error('Get category revenue error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve category revenue',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get daily earnings for current month
     *
     * @return JsonResponse
     */
    public function getDailyEarnings(): JsonResponse
    {
        try {
            $earnings = $this->reportService->getDailyEarningsCurrentMonth();

            return response()->json([
                'success' => true,
                'data' => [
                    'month' => now()->format('F Y'),
                    'daily_earnings' => $earnings
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Get daily earnings error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve daily earnings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get order status breakdown
     *
     * @return JsonResponse
     */
    public function getOrderStatus(): JsonResponse
    {
        try {
            $status = $this->reportService->getOrderStatusBreakdown();

            return response()->json([
                'success' => true,
                'data' => $status
            ], 200);
        } catch (\Exception $e) {
            Log::error('Get order status error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve order status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payment method breakdown
     *
     * @return JsonResponse
     */
    public function getPaymentMethods(): JsonResponse
    {
        try {
            $methods = $this->reportService->getPaymentMethodBreakdown();

            return response()->json([
                'success' => true,
                'data' => $methods
            ], 200);
        } catch (\Exception $e) {
            Log::error('Get payment methods error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve payment methods',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get top customers by spending
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getTopCustomers(Request $request): JsonResponse
    {
        try {
            $limit = $request->input('limit', 10);
            $customers = $this->reportService->getTopCustomersBySpending($limit);

            return response()->json([
                'success' => true,
                'data' => $customers
            ], 200);
        } catch (\Exception $e) {
            Log::error('Get top customers error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve top customers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get revenue comparison
     *
     * @return JsonResponse
     */
    public function getRevenueComparison(): JsonResponse
    {
        try {
            $comparison = $this->reportService->getRevenueComparison();

            return response()->json([
                'success' => true,
                'data' => $comparison
            ], 200);
        } catch (\Exception $e) {
            Log::error('Get revenue comparison error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve revenue comparison',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get summary report
     *
     * @return JsonResponse
     */
    public function getSummaryReport(): JsonResponse
    {
        try {
            $summary = $this->reportService->getSummaryReport();

            return response()->json([
                'success' => true,
                'data' => $summary
            ], 200);
        } catch (\Exception $e) {
            Log::error('Get summary report error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve summary report',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate PDF report
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function generatePdf(Request $request): JsonResponse
    {
        try {
            $options = $request->all();
            $pdf = $this->reportService->generatePdfReport($options);

            return response()->json([
                'success' => true,
                'data' => $pdf
            ], 200);
        } catch (\Exception $e) {
            Log::error('Generate PDF error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate PDF report',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate Excel report
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function generateExcel(Request $request): JsonResponse
    {
        try {
            $options = $request->all();
            $excel = $this->reportService->generateExcelReport($options);

            return response()->json([
                'success' => true,
                'data' => $excel
            ], 200);
        } catch (\Exception $e) {
            Log::error('Generate Excel error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate Excel report',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
