<?php

namespace App\Services\implementation;

use App\Dtos\TableNumberDto;
use App\Exceptions\TableNumberNotFoundException;
use App\Mappers\TableNumberMapper;
use App\Models\TableNumber;
use App\Repositories\implement\TableNumberRepositoryImplementation;
use App\Repositories\Interfaces\TableNumberRepositoryInterface;
use Illuminate\Support\Collection;

class TableNumberServiceImplementation implements TableNumberRepositoryInterface
{
    protected TableNumberRepositoryImplementation $tableNumberRepository;

    public function __construct(TableNumberRepositoryImplementation $tableNumberRepository) {
        $this->tableNumberRepository = $tableNumberRepository;
    }

    public function getAllTableNumbers(): Collection
    {
        return $this->tableNumberRepository->getAllTableNumbers();
    }

    /**
     * @throws TableNumberNotFoundException
     */
    public function getTableNumberById(int $id): TableNumber
    {
        return $this->tableNumberRepository->getTableNumberById($id);
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
    public function updateTableNumber(TableNumberDto $tableNumberDto, int $id): TableNumber
    {
       return $this->tableNumberRepository->updateTableNumber($tableNumberDto, $id);
    }

    /**
     * @throws TableNumberNotFoundException
     */
    public function deleteTableNumber(int $id): bool
    {
        return $this->tableNumberRepository->deleteTableNumber($id);
    }
}
