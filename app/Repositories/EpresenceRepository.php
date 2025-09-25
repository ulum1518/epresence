<?php

namespace App\Repositories;

use App\Interfaces\EpresenceRepositoryInterface;
use App\Models\Epresence;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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

     public function approve(int $presenceId, int $approverId): array
    {
        $presenceToApprove = Epresence::with('user')->find($presenceId);

        if (!$presenceToApprove) {
            return ['status' => 'error', 'code' => 404, 'message' => 'Data presensi tidak ditemukan.'];
        }

        if ($presenceToApprove->is_approve) {
            return ['status' => 'error', 'code' => 409, 'message' => 'Data presensi ini sudah di-approve sebelumnya.'];
        }

        $approver = User::find($approverId);

        if ($approver->npp !== $presenceToApprove->user->npp_supervisor) {
            return ['status' => 'error', 'code' => 403, 'message' => 'Anda tidak memiliki hak untuk menyetujui presensi ini.'];
        }

        $presenceToApprove->is_approve = true;
        $presenceToApprove->save();

        return ['status' => 'success', 'data' => $presenceToApprove];
    }

     public function getRecapData(): Collection
    {
        $recapData = DB::table('epresences')
            ->join('users', 'epresences.id_users', '=', 'users.id')
            ->select(
                'users.id as id_user',
                'users.nama as nama_user',
                DB::raw("waktu::date as tanggal"),
                DB::raw("MAX(CASE WHEN type = 'IN' THEN waktu::time END) as waktu_masuk"),
                DB::raw("MAX(CASE WHEN type = 'OUT' THEN waktu::time END) as waktu_pulang"),
                DB::raw("BOOL_OR(CASE WHEN type = 'IN' THEN is_approve END) as is_approve_masuk"),
                DB::raw("BOOL_OR(CASE WHEN type = 'OUT' THEN is_approve END) as is_approve_pulang")
            )
            ->groupBy('id_user', 'nama_user', 'tanggal')
            ->orderBy('tanggal', 'desc')
            ->orderBy('nama_user', 'asc')
            ->get();

        return $recapData->map(function ($row) {
            return [
                'id_user' => $row->id_user,
                'nama_user' => $row->nama_user,
                'tanggal' => $row->tanggal,
                'waktu_masuk' => $row->waktu_masuk,
                'waktu_pulang' => $row->waktu_pulang,
                'status_masuk' => $this->resolveStatus($row->waktu_masuk, $row->is_approve_masuk),
                'status_pulang' => $this->resolveStatus($row->waktu_pulang, $row->is_approve_pulang),
            ];
        });
    }

    private function resolveStatus($waktu, $isApprove): ?string
    {
        if (is_null($waktu)) {
            return null;
        }
        $isApproveBool = filter_var($isApprove, FILTER_VALIDATE_BOOLEAN);

        return $isApproveBool ? 'APPROVE' : 'PENDING';
    }
}