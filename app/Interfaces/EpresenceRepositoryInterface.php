<?php

namespace App\Interfaces;

use App\Models\Epresence;
use Illuminate\Support\Collection;

interface EpresenceRepositoryInterface
{
    public function createPresence(array $data, int $userId): array;
    public function approve(int $presenceId, int $approverId): array;
    public function getRecapData(): Collection;
}