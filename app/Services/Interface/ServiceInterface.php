<?php

namespace App\Services\Interface;

use App\Models\TableNumber;
use Illuminate\Support\Collection;

interface ServiceInterface
{
    public function getAttTableNumbers(): Collection;
    public function getTableNumberById(int $id): ?TableNumber;
    public function createTableNumber(TableNumber $tableNumber): TableNumber;
    public function updateTableNumber(TableNumber $tableNumber, int $id): ?TableNumber;
    public function deleteTableNumber(int $id): bool;
}
