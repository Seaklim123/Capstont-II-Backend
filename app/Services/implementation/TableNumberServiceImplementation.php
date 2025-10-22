<?php

namespace App\Services\implementation;

use App\Dtos\TableNumberDto;
use App\Exceptions\TableNumberNotFoundException;
use App\Models\TableNumber;
use App\Repositories\implement\TableNumberRepositoryImplementation;
use App\Services\Interface\TableNumberServiceInterface;
use Illuminate\Support\Collection;

class TableNumberServiceImplementation implements TableNumberServiceInterface
{
    protected TableNumberRepositoryImplementation $tableNumberRepository;

    public function __construct(TableNumberRepositoryImplementation $tableNumberRepository) {
        $this->tableNumberRepository = $tableNumberRepository;
    }

    public function getAttTableNumbers(): Collection
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
        return $this->tableNumberRepository->createTableNumber($tableNumberDto);
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
