<?php

namespace App\Repositories\Interfaces;

use App\Models\TableNumber;
use Illuminate\Support\Collection;

interface TableNumberRepositoryInterface
{
    public function getAllTableNumbers(): Collection;
    public function getTableNumberById(int $id): ?TableNumber;
    public function createTableNumber(array $data): TableNumber;
    public function updateTableNumber(array $data, int $id): TableNumber;
    public function deleteTableNumber(int $id): bool;
}
