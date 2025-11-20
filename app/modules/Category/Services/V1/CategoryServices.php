<?php

namespace Modules\Category\Services\V1;

use Modules\Category\Repositories\CategoryRepository;
use App\Filament\Resources\CategoryResource;

class CategoryServices
{
    protected CategoryRepository $categoryRepository;
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategories()
    {
        $categories = $this->categoryRepository->getAllCategories();
        return CategoryResource::collection($categories);
    }
    public function getCategoryById($id)
    {
        $categories = $this->categoryRepository->getCategoryById($id);
        return new CategoryResource($categories);
    }
    public function createCategory($data)
    {
        $categories = $this->categoryRepository->createCategory($data);
        return new CategoryResource($categories);
    }
    public function updateCategory($id, $data)
    {
        $categories = $this->categoryRepository->updateCategory($id, $data);
        return new CategoryResource($categories);
    }
    public function deleteCategory($id)
    {
        return $this->categoryRepository->deleteCategory($id);
    }

}