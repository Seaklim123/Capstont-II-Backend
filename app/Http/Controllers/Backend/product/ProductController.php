<?php

namespace App\Http\Controllers\Backend\product;

use App\Http\Controllers\Controller;
use App\Http\Requests\backend\products\CreateProductRequest;
use App\Http\Requests\backend\products\UpdateProductRequest;
use App\Http\Resources\Backend\products\ProductResource;
use App\Services\ProductServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductServices $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = $this->productService->getAllProducts();
        return ProductResource::collection($products);
    }

    public function show(int $id)
    {
        $product = $this->productService->getProduct($id);
        return new ProductResource($product);
    }

    public function store(CreateProductRequest $request)
    {
        $product = $this->productService->createProduct($request->validated());
        return new ProductResource($product);
    }

    public function update(UpdateProductRequest $request, int $id)
    {
        $product = $this->productService->updateProduct($id, $request->validated());
        return new ProductResource($product);
    }

    public function destroy(int $id)
    {
        $this->productService->deleteProduct($id);
        return response()->json(['message' => 'Category deleted successfully']);
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

            $products = $this->productService->getProductsWithMostOrders($limit, $startDate, $endDate);

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
     * Delete multiple products
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteMultiple(Request $request): JsonResponse
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'ids' => 'required|array|min:1',
                'ids.*' => 'required|integer|exists:products,id'
            ], [
                'ids.required' => 'Product IDs are required',
                'ids.array' => 'Product IDs must be an array',
                'ids.min' => 'At least one product ID is required',
                'ids.*.integer' => 'Each product ID must be an integer',
                'ids.*.exists' => 'One or more product IDs do not exist'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Delete products
            $result = $this->productService->deleteMultipleProducts($request->input('ids'));

            // Determine response status based on results
            if ($result['error_count'] === 0) {
                // All deletions successful
                return response()->json([
                    'success' => true,
                    'message' => "Successfully deleted {$result['success_count']} product(s)",
                    'data' => $result
                ], 200);
            } elseif ($result['success_count'] > 0) {
                // Partial success
                return response()->json([
                    'success' => true,
                    'message' => "Deleted {$result['success_count']} product(s), but {$result['error_count']} failed",
                    'data' => $result
                ], 207); // 207 Multi-Status
            } else {
                // All failed
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete all products',
                    'data' => $result
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Delete multiple products error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
