<?php

namespace App\Services;
use App\Repositories\Interfaces\CategoryRepositoriesInterfaces;

class CategoryServices{
    protected CategoryRepositoriesInterfaces $categoryrepo;

    public function __construct(CategoryRepositoriesInterfaces $categoryrepo){
        $this->categoryrepo = $categoryrepo;
    }
    public function allCategroy(){
        return $this->categoryrepo->getCategory();
    }

    public function createCategory($data){
        return $this->categoryrepo->createCategory($data);
    }
}

