<?php

namespace App\Http\Controllers\Backend\dashboard;

use App\Http\Controllers\Controller;
use App\Services\implementation\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Get comprehensive dashboard statistics
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $stats = $this->dashboardService->getDashboardStats();

            return response()->json([
                'success' => true,
                'data' => $stats
            ], 200);
        } catch (\Exception $e) {
            Log::error('Dashboard index error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve dashboard statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get products with most orders
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function topProducts(Request $request): JsonResponse
    {
        try {
            $limit = $request->input('limit', 10);
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $products = $this->dashboardService->getProductsWithMostOrders($limit, $startDate, $endDate);

            return response()->json([
                'success' => true,
                'data' => $products
            ], 200);
        } catch (\Exception $e) {
            Log::error('Top products error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve top products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search categories
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchCategories(Request $request): JsonResponse
    {
        try {
            $searchTerm = $request->input('search', '');
            $limit = $request->input('limit', 10);

            if (empty($searchTerm)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Search term is required'
                ], 400);
            }

            $categories = $this->dashboardService->searchCategories($searchTerm, $limit);

            return response()->json([
                'success' => true,
                'data' => $categories
            ], 200);
        } catch (\Exception $e) {
            Log::error('Search categories error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to search categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search products by name
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchProducts(Request $request): JsonResponse
    {
        try {
            $searchTerm = $request->input('search', '');
            $categoryId = $request->input('category_id');
            $limit = $request->input('limit', 20);

            if (empty($searchTerm)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Search term is required'
                ], 400);
            }

            $products = $this->dashboardService->searchProductsByName($searchTerm, $categoryId, $limit);

            return response()->json([
                'success' => true,
                'data' => $products
            ], 200);
        } catch (\Exception $e) {
            Log::error('Search products error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to search products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get earnings statistics
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function earnings(Request $request): JsonResponse
    {
        try {
            $type = $request->input('type', 'all'); // all, today, month, range
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $date = $request->input('date');

            $data = [];

            switch ($type) {
                case 'today':
                    $data['earnings'] = $this->dashboardService->getEarningsByDate($date);
                    $data['type'] = 'today';
                    break;
                case 'month':
                    $data['earnings'] = $this->dashboardService->getEarningsByCurrentMonth();
                    $data['type'] = 'current_month';
                    break;
                case 'range':
                    if (!$startDate || !$endDate) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Start date and end date are required for range type'
                        ], 400);
                    }
                    $data['earnings'] = $this->dashboardService->getEarningsByDateRange($startDate, $endDate);
                    $data['type'] = 'range';
                    $data['start_date'] = $startDate;
                    $data['end_date'] = $endDate;
                    break;
                default:
                    $data['earnings'] = $this->dashboardService->getTotalEarnings();
                    $data['type'] = 'all_time';
            }

            return response()->json([
                'success' => true,
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            Log::error('Earnings error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve earnings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get orders statistics
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function orders(Request $request): JsonResponse
    {
        try {
            $data = [
                'total' => $this->dashboardService->getTotalOrders(),
                'today' => $this->dashboardService->getOrdersByDate(),
                'completed' => $this->dashboardService->getTotalCompletedOrders(),
                'pending' => $this->dashboardService->getTotalPendingOrders(),
                'cancelled' => $this->dashboardService->getTotalCancelledOrders(),
                'average_value' => $this->dashboardService->getAverageOrderValue(),
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            Log::error('Orders statistics error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve order statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get daily earnings chart data
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function earningsChart(Request $request): JsonResponse
    {
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            if (!$startDate || !$endDate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Start date and end date are required'
                ], 400);
            }

            $chartData = $this->dashboardService->getDailyEarningsChart($startDate, $endDate);

            return response()->json([
                'success' => true,
                'data' => $chartData
            ], 200);
        } catch (\Exception $e) {
            Log::error('Earnings chart error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve chart data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get category performance
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function categoryPerformance(Request $request): JsonResponse
    {
        try {
            $limit = $request->input('limit', 10);
            $performance = $this->dashboardService->getCategoryPerformance($limit);

            return response()->json([
                'success' => true,
                'data' => $performance
            ], 200);
        } catch (\Exception $e) {
            Log::error('Category performance error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve category performance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get financial summary
     *
     * @return JsonResponse
     */
    public function financialSummary(): JsonResponse
    {
        try {
            $data = $this->dashboardService->getFinancialSummary();

            return response()->json([
                'success' => true,
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            Log::error('Financial summary error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve financial summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
