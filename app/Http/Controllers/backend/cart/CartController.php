<?php

namespace App\Http\Controllers\backend\cart;

use App\Http\Controllers\Controller;
use App\Http\Requests\backend\cart\CreateCartRequest;
use App\Http\Requests\backend\cart\UpdateCartRequest;
use App\Http\Requests\backend\category\CreateCategoryRequest;
use App\Http\Resources\Backend\cart\CartResource;
use App\Http\Resources\Backend\category\CategoryResource;
use App\Services\CartServices;
use App\Services\CategoryServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
protected $cartService;

    public function __construct(CartServices $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $carts = $this->cartService->getAllCarts();
        return CartResource::collection($carts);
    }

    public function store(CreateCartRequest $request)
    {
        $cart = $this->cartService->createCart($request->validated());
        return new CartResource($cart);
    }

    public function show(int $id)
    {
        $cart = $this->cartService->findCart($id);
        return new CartResource($cart);
    }

    public function update(UpdateCartRequest $request, int $id)
    {
        $cart = $this->cartService->updateCart($id, $request->validated());
        return new CartResource($cart);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->cartService->deleteCart($id);
        return response()->json(['message' => 'Cart deleted successfully.']);
    }
}
