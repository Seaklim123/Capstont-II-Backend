<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoriesInterfaces;

class CategoryRepositories implements CategoryRepositoriesInterfaces{
    public function getCategory(){
        return Category::all();
    }
    public function createCategory($data){
        $category = Category::create($data);
        return $category;
    }

    public function findCategory($id)
    {
        // TODO: Implement findCategory() method.
    }

    public function updateCategory(int $id, $data)
    {
        // TODO: Implement updateCategory() method.
    }

    public function deleteCategory(int $id)
    {
        // TODO: Implement deleteCategory() method.
    }
}
