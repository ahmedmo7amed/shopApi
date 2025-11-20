<?php

namespace Modules\Product\Repositories;
use Modules\Product\Models\Product;


class ProductRepository
    {
        protected  Product $productModel;

        public function __construct(Product $productModel)
        {
            $this->productModel = $productModel;
        }
        
        public function getAllProducts($filters = [], $perPage = 12)
        {
            return Product::with('options.values')
            ->when(isset($filters['category_id']), function ($query) use ($filters) {
                $query->where('category_id', $filters['category_id']);
            })
            ->when(isset($filters['option_id']), function ($query) use ($filters) {
                $query->whereHas('options', function ($query) use ($filters) {
                    $query->where('id', $filters['option_id']);
                });
            })
            ->paginate($perPage);
        }
        public function search($query, $perPage = 10)
        {
            return Product::where('name', 'like', "%$query%")
                ->orWhere('description', 'like', "%$query%")
                ->paginate($perPage);
        }
        public function related($categoryId, $excludeId, $limit = 4)
        {
            return Product::where('category_id', $categoryId)
                ->where('id', '!=', $excludeId)
                ->take($limit)
                ->get();
        }

        public function byCategory($categoryId, $perPage = 10)
        {
            return Product::where('category_id', $categoryId)
                ->paginate($perPage);
        }

        public function getProductById($id)
        {
            return $this->productModel->with('options.values')->findOrFail($id);
        }
        public function createProduct($data)
        {
            return $this->productModel->create($data);
        }
        public function updateProduct($id, $data)
        {
            return $this->productModel->findOrFail($id)->update($data);
        }
        public function deleteProduct($id)
        {
            return $this->productModel->findOrFail($id)->delete();
        }
    }