<?php
namespace App\Repositories\Interfaces;
interface CategoryRepositoriesInterfaces{
    public function getCategory();
    public function createCategory($data);
    public function findCategory($id);
    public function updateCategory(int $id, $data);
    public function deleteCategory(int $id);
}
