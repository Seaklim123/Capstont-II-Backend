<?php

namespace App\Services\Interface;

use App\Dtos\TableNumberDto;
use App\Models\TableNumber;
use Illuminate\Support\Collection;

interface TableNumberServiceInterface
{
    public function getAttTableNumbers(): Collection;
    public function getTableNumberById(int $id): ?TableNumber;
    public function createTableNumber(TableNumberDto $tableNumberDto): TableNumber;
    public function updateTableNumber(TableNumberDto $tableNumberDto, int $id): ?TableNumber;
    public function deleteTableNumber(int $id): bool;
}
