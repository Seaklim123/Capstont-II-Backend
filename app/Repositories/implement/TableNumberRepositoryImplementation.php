<?php

namespace App\Repositories\implement;

use App\Dtos\TableNumberDto;
use App\Exceptions\TableNumberNotFoundException;
use App\Mappers\TableNumberMapper;
use App\Models\TableNumber;
use App\Repositories\Interfaces\TableNumberRepositoryInterface;
use Illuminate\Support\Collection;

class TableNumberRepositoryImplementation implements TableNumberRepositoryInterface
{
    public function getAllTableNumbers(): Collection
    {
        return TableNumber::all();
    }

    /**
     * @throws TableNumberNotFoundException
     */
    public function getTableNumberById(int $id): ?TableNumber
    {
        $table = TableNumber::find($id);
        if(!$table){
            throw new TableNumberNotFoundException();
        }
        return TableNumber::find($id);
    }

    public function createTableNumber(TableNumberDto $tableNumberDto): TableNumber
    {
        $table = TableNumberMapper::tableNumberMapper($tableNumberDto);
        $table->save();
        return $table;
    }

    /**
     * @throws TableNumberNotFoundException
     */
    public function updateTableNumber(TableNumberDto $tableNumberDto, int $id): ?TableNumber
    {
        $table = TableNumber::find($id);
        if(!$table){
            throw new TableNumberNotFoundException();
        }
        $updatedTableModel = TableNumberMapper::tableNumberMapper($tableNumberDto);
        $table->update($updatedTableModel->getAttributes());
        return $table;
    }

    /**
     * @throws TableNumberNotFoundException
     */
    public function deleteTableNumber(int $id): bool
    {
        $table = TableNumber::find($id);
        if (!$table) {
            throw new TableNumberNotFoundException();
        }
        return $table->delete();
    }
}
