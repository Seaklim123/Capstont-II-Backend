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
        $category = Category::find($id);
        return $category;
    }

    public function updateCategory(int $id, $data)
    {
        $category = Category::find($id);
        $category->update($data);
        return $category;
    }

    public function deleteCategory(int $id)
    {
        $category = Category::find($id);
        $category->delete();
        return $category;
    }
}
