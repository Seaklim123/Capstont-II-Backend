<?php
namespace App\Repositories\Interfaces;
interface CartRepositoriesInterfaces{
    public function getAll();
    public function findById(int $id);
    public function findBytable(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}