<?php

namespace App\Repositories;

use App\Interfaces\EpresenceRepositoryInterface;
use App\Models\Epresence;
use Carbon\Carbon;

class EpresenceRepository implements EpresenceRepositoryInterface
{
    public function createPresence(array $data, int $userId): array
    {
        $type = $data['type'];
        $waktu = $data['waktu'];

        $presenceDate = Carbon::parse($waktu)->toDateString();

        $existingPresence = Epresence::where('id_users', $userId)
            ->where('type', $type)
            ->whereDate('waktu', $presenceDate)
            ->first();

        if ($existingPresence) {
            return [
                'status' => 'error',
                'message' => 'Anda sudah melakukan absensi ' . $type . ' untuk hari ini.'
            ];
        }

        $epresence = Epresence::create([
            'id_users' => $userId,
            'type' => $type,
            'waktu' => $waktu,
            'is_approve' => false,
        ]);

        return [
            'status' => 'success',
            'data' => $epresence
        ];
    }
}