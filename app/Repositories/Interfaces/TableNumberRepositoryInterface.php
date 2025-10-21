<?php

namespace App\Repositories\Interfaces;

use App\Dtos\TableNumberDto;
use App\Models\TableNumber;
use Illuminate\Support\Collection;

interface TableNumberRepositoryInterface
{
    public function getAllTableNumbers(): Collection;
    public function getTableNumberById(int $id): ?TableNumber;
    public function createTableNumber(TableNumberDto $tableNumberDto): TableNumber;
    public function updateTableNumber(TableNumberDto $tableNumberDto, int $id): ?TableNumber;
    public function deleteTableNumber(int $id): bool;
}
