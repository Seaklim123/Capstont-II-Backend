<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use PhpParser\Node\Expr\Cast\Double;

interface ProductRepositoriesInterfaces{
    public function all();
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);

    // public function getProductsMostPurchased(string $name, Double $totalPrice): Collection;
}
