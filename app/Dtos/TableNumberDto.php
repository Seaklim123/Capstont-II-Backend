<?php

namespace App\Dtos;

class TableNumberDto
{
    public function __construct(
        public int $number,
        public string $status
    ){}
}
