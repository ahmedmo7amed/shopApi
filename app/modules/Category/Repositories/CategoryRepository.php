<?php

namespace Modules\Category\Repositories;
use Modules\Category\Models\Category;

class CategoryRepository
    {
     
        protected  Category $categoryModel;

        public function __construct(Category $categoryModel)
        {
            $this->categoryModel = $categoryModel;
        }
        
        public function getAllCategories()
        {
            return $this->categoryModel->all();
        }
        public function getCategoryById($id)
        {
           
            return $this->categoryModel->findOrFail($id);
        }
        public function createCategory($data)
        {
            return $this->categoryModel->create($data);
        }
        public function updateCategory($id, $data)
        {
            return $this->categoryModel->findOrFail($id)->update($data);
        }
        public function deleteCategory($id)
        {
            return $this->categoryModel->findOrFail($id)->delete();
        }
   
    }