<?php

namespace App\Repository;

use App\Model\MarkerTypeEnum;

class ScaffoldRepository
{
    public function __construct(
        public MarkerRepository $markerRepository,
    ) {}

    public function getType(MarkerTypeEnum $type): string
    {
        return $type->folder();
    }
}
