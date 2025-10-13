<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoriesInterfaces;
use Illuminate\Support\Facades\Storage;

class ProductServices{
    protected $productRepository;

    public function __construct(ProductRepositoriesInterfaces $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts()
    {
        return $this->productRepository->all();
    }

    public function getProduct(int $id)
    {
        return $this->productRepository->find($id);
    }

    public function createProduct(array $data)
    {
        if (isset($data['image_path'])) {
            $data['image_path'] = $data['image_path']->store('products', 'public');
            unset($data['image_path']);
        }
        return $this->productRepository->create($data);
    }

    public function updateProduct(int $id, array $data)
    {
        if (isset($data['image_path'])) {
            $product = $this->productRepository->find($id);
            if ($product && $product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $data['image_path']->store('products', 'public');
            unset($data['image_path']);
        }
        return $this->productRepository->update($id, $data);
    }

    public function deleteProduct(int $id)
    {
        $product = $this->productRepository->find($id);
        if ($product && $product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        return $this->productRepository->delete($id);
    }
}