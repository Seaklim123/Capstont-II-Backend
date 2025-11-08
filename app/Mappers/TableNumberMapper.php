<?php

namespace App\Mappers;

use App\Dtos\TableNumberDto;
use App\Models\TableNumber;

class TableNumberMapper
{
    public static function tableNumberMapper(TableNumberDto $tableNumberDto): TableNumber {
        return new TableNumber([
            'number' => $tableNumberDto->number,
            'status' => $tableNumberDto->status,
        ]);
    }
}
