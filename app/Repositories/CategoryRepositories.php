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
}
