<?php

namespace App\Mappers;

use App\Dtos\TableNumberDto;

class TableNumberMapper
{
    public static function tableNumberMapper(TableNumberDto $tableNumberDto): array {
        return [
            'number' => $tableNumberDto->number,
            'status' => $tableNumberDto->status,
        ];
    }
}
