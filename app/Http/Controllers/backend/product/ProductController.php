<?php

namespace App\Http\Controllers\backend\product;

use App\Http\Controllers\Controller;
use App\Http\Requests\backend\products\CreateProductRequest;
use App\Http\Requests\backend\products\UpdateProductRequest;
use App\Http\Resources\Backend\products\ProductResource;
use App\Services\ProductServices;

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
        dd($request->validated());
        $product = $this->productService->updateProduct($id, $request->validated());
        return new ProductResource($product);
    }

    public function destroy(int $id)
    {
        $this->productService->deleteProduct($id);
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
