<?php

use App\Filament\Resources\ProductResource;
use Modules\Product\Models\Product;
use Modules\Category\Models\Category;
use Modules\Product\Repositories\ProductRepository;


class ProductServices
    {
        protected  ProductRepository $productRepository;

        public function __construct(ProductRepository $productRepository)
        {
            $this->productRepository = $productRepository;
        }
        public function getAllProducts($filters = [], $perPage = 12)
        {
            $products = $this->productRepository->getAllProducts($filters , $perPage );
            return ProductResource::collection($products);
        }
        public function search($query, $perPage = 10)
        {
            $products = $this->productRepository->search($query , $perPage );
            return ProductResource::collection($products);
        }
        public function related($categoryId, $excludeId, $limit = 4)
        {
            $products = $this->productRepository->related($categoryId , $excludeId , $limit );
            return ProductResource::collection($products);
        }
        public function byCategory($categoryId, $perPage = 10)
        {
            $products = $this->productRepository->byCategory($categoryId , $perPage );
            return ProductResource::collection($products);
        }

        public function getProductById($id)
        {
            $products = $this->productRepository->getProductById($id);
            $relatedProducts = $this->related($products->category_id, $products->id);

            return [
                'product' => new ProductResource($products),
                'related_products' => $relatedProducts,
                'categories' => $products->category
            ];
        }
        public function getProductsByCategory($categorySlug, $perPage = 10)
        {
            $category = Category::where('slug', $categorySlug)->firstOrFail();
            $products = $this->productRepository->byCategory($category->id, $perPage);

            return [
                'category' => $category,
                'products' => $products,
            ];
        }

        public function createProduct($data)
        {
            $products = $this->productRepository->createProduct($data);
            return new ProductResource($products);
        }
        public function updateProduct($id, $data)
        {
            $products = $this->productRepository->updateProduct($id, $data);
            return new ProductResource($products);
        }
        public function deleteProduct($id)
        {
            return $this->productRepository->deleteProduct($id);
        }
    }
