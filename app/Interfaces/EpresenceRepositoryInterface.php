<?php

namespace App\Interfaces;

use App\Models\Epresence;

interface EpresenceRepositoryInterface
{
    public function createPresence(array $data, int $userId): array;
}