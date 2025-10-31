<?php

namespace App\Services;

use App\Dtos\TableNumberDto;
use App\Exceptions\TableNumberNotFoundException;
use App\Mappers\TableNumberMapper;
use App\Models\TableNumber;
use App\Repositories\implement\TableNumberRepository;
use Illuminate\Support\Collection;

class TableNumberService
{
    public function __construct(protected TableNumberRepository $tableNumberRepository) {}

    public function getAllTableNumbers(): Collection
    {
        return $this->tableNumberRepository->getAllTableNumbers();
    }

    /**
     * @throws TableNumberNotFoundException
     */
    public function getTableNumberById(int $id): TableNumber
    {
        $table = $this->tableNumberRepository->getTableNumberById($id);
        if (!$table) {
            throw new TableNumberNotFoundException();
        }
        return $table;
    }

    public function createTableNumber(TableNumberDto $dto): TableNumber
    {
        $data = TableNumberMapper::tableNumberMapper($dto);
        return $this->tableNumberRepository->createTableNumber($data);
    }

    /**
     * @throws TableNumberNotFoundException
     */
    public function updateTableNumber(TableNumberDto $dto, int $id): TableNumber
    {
        $existing = $this->tableNumberRepository->getTableNumberById($id);
        if (!$existing) {
            throw new TableNumberNotFoundException();
        }
        $data = TableNumberMapper::tableNumberMapper($dto);
        return $this->tableNumberRepository->updateTableNumber($data, $id);
    }

    /**
     * @throws TableNumberNotFoundException
     */
    public function deleteTableNumber(int $id): bool
    {
        $table = $this->tableNumberRepository->getTableNumberById($id);
        if (!$table) {
            throw new TableNumberNotFoundException();
        }
        return $this->tableNumberRepository->deleteTableNumber($id);
    }
}
