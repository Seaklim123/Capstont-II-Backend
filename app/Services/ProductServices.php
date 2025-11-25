<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoriesInterfaces;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

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
        // Debug logging
        \Log::info('ProductServices createProduct called with data:', $data);

        if (isset($data['image_path'])) {
            \Log::info('Processing image_path - Type: ' . gettype($data['image_path']));

            if ($data['image_path'] instanceof UploadedFile) {
                // It's a file upload - store it and keep the path
                \Log::info('Processing file upload');
                $storedPath = $data['image_path']->store('products', 'public');
                $data['image_path'] = $storedPath; // Keep the stored path
                \Log::info('File stored at: ' . $storedPath);

            } else if (is_string($data['image_path']) && !empty($data['image_path'])) {
                // It's a URL string - validate and keep it
                \Log::info('Processing URL: ' . $data['image_path']);

                if (filter_var($data['image_path'], FILTER_VALIDATE_URL)) {
                    // Valid URL - keep as is
                    \Log::info('Valid URL accepted');
                } else {
                    // Invalid URL - remove it
                    \Log::warning('Invalid URL format, removing');
                    unset($data['image_path']);
                }

            } else {
                // Invalid image_path type
                \Log::warning('Invalid image_path type: ' . gettype($data['image_path']));
                unset($data['image_path']);
            }
        }

        // Handle discount - ensure it's properly formatted
        if (isset($data['discount'])) {
            $data['discount'] = floatval($data['discount']);
            \Log::info('Discount processed: ' . $data['discount']);
        }

        \Log::info('Final data for repository create:', $data);
        $result = $this->productRepository->create($data);
        \Log::info('Repository create result: ' . ($result ? 'success - ID: ' . $result->id : 'failed'));

        return $result;
    }

    public function updateProduct(int $id, array $data)
    {
        // Debug logging
        \Log::info('ProductServices updateProduct called with data:', $data);

        if (isset($data['image_path'])) {
            $product = $this->productRepository->find($id);
            \Log::info('Processing image_path for update - Type: ' . gettype($data['image_path']));

            if ($data['image_path'] instanceof UploadedFile) {
                // File upload - delete old file if exists and store new one
                \Log::info('Processing file upload for update');

                if ($product && $product->image_path && !filter_var($product->image_path, FILTER_VALIDATE_URL)) {
                    Storage::disk('public')->delete($product->image_path);
                    \Log::info('Deleted old file: ' . $product->image_path);
                }

                $storedPath = $data['image_path']->store('products', 'public');
                $data['image_path'] = $storedPath; // Keep the stored path
                \Log::info('New file stored at: ' . $storedPath);

            } else if (is_string($data['image_path']) && !empty($data['image_path'])) {
                // URL string
                \Log::info('Processing URL for update: ' . $data['image_path']);

                if (filter_var($data['image_path'], FILTER_VALIDATE_URL)) {
                    // Valid URL - delete old file if switching from file to URL
                    if ($product && $product->image_path && !filter_var($product->image_path, FILTER_VALIDATE_URL)) {
                        Storage::disk('public')->delete($product->image_path);
                        \Log::info('Deleted old file when switching to URL');
                    }
                } else {
                    \Log::warning('Invalid URL format, removing');
                    unset($data['image_path']);
                }

            } else {
                \Log::warning('Invalid image_path type for update');
                unset($data['image_path']);
            }
        }

        // Handle discount - ensure it's properly formatted
        if (isset($data['discount'])) {
            $data['discount'] = floatval($data['discount']);
            \Log::info('Discount processed for update: ' . $data['discount']);
        }

        \Log::info('Final data for repository update:', $data);
        $result = $this->productRepository->update($id, $data);
        \Log::info('Repository update result: ' . ($result ? 'success' : 'failed'));

        return $result;
    }

    public function deleteProduct(int $id)
    {
        $product = $this->productRepository->find($id);
        if ($product && $product->image_path && !filter_var($product->image_path, FILTER_VALIDATE_URL)) {
            // Only delete if it's a stored file, not a URL
            Storage::disk('public')->delete($product->image_path);
        }
        return $this->productRepository->delete($id);
    }


}
