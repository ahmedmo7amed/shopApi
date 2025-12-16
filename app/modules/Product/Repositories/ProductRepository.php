<?php

namespace Modules\Product\Repositories;
use Modules\Product\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;


class ProductRepository
    {
        protected  Product $productModel;

        public function __construct(Product $productModel)
        {
            $this->productModel = $productModel;
        }

        public function getAllProducts($filters = [], $perPage = 12): LengthAwarePaginator
        {
            return $this->productModel
            ->with('options.values', 'category')
            ->when( !empty($filters['category_id']),
                fn (Builder $q) => $q
                ->where('category_id', $filters['category_id']))
            ->when( !empty($filters['option_id']),
                fn (Builder $q) => $q
                ->whereHas('options', fn (Builder $q) => $q
                ->where('id', $filters['option_id'])))
            ->paginate($perPage);
        }
        public function search($query, $perPage = 10)
        {
            return $this->productModel
            ->where('name', 'like', "%$query%")
                ->orWhere('description', 'like', "%$query%")
                ->paginate($perPage);
        }
         public function findWithOptions(int $id): Product
        {
            return $this->productModel
                ->with('options.values')
                ->findOrFail($id);
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
