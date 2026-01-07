<?php

declare(strict_types=1);

namespace ShipMonk;

enum SortOrder: string
{
    case ASC = 'asc';
    case DESC = 'desc';

    public function getComparatorResult(): int
    {
        return $this === self::ASC ? -1 : 1;
    }
}
