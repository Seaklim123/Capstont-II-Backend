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

        if (isset($data['image_path']) && $data['image_path'] !== null) {
            // Store image in storage/app/public/category
            $path = $data['image_path']->store('category', 'public');
            $data['image_path'] = $path; // Replace uploaded file with its path
        }
        $category = $this->categoryrepo->createCategory($data);
        
        return $category;
    }
    public function findCategory( $id){
        return $this->categoryrepo->findCategory($id);        
    }
    public function updateCategory($id, array $data){
        if (isset($data['image_path']) && $data['image_path'] !== null) {
            // Store image in storage/app/public/category
            $path = $data['image_path']->store('category', 'public');
            $data['image_path'] = $path; // Replace uploaded file with its path
        }
        return $this->categoryrepo->updateCategory($id, $data);
         
    }

    public function deleteCategory(int $id){
        return $this->categoryrepo->deleteCategory($id);
    }

    
}

