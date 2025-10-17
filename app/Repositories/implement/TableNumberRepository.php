<?php

namespace App\Repositories\implement;

use App\Exceptions\TableNumberNotFoundException;
use App\Models\TableNumber;
use App\Repositories\Interfaces\TableNumberRepositoryInterface;
use Illuminate\Support\Collection;

class TableNumberRepository implements TableNumberRepositoryInterface
{
    public function getAllTableNumbers(): Collection
    {
        return TableNumber::all();
    }

    public function getTableNumberById(int $id): ?TableNumber
    {
        return TableNumber::find($id);
    }

    public function createTableNumber(array $data): TableNumber
    {
        return TableNumber::create($data);
    }

    /**
     * @throws TableNumberNotFoundException
     */
    public function updateTableNumber(array $data, int $id): TableNumber
    {
        $table = TableNumber::find($id);
        if (!$table) {
            throw new TableNumberNotFoundException();
        }
        $table->update($data);
        return $table;
    }

    public function deleteTableNumber(int $id): bool
    {
        $table = TableNumber::find($id);
        if (!$table) {
            return false;
        }
        return (bool) $table->delete();
    }
}
