<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Interfaces\EpresenceRepositoryInterface;
use App\Models\Epresence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EpresenceController extends Controller
{
    private EpresenceRepositoryInterface $epresenceRepository;

    public function __construct(EpresenceRepositoryInterface $epresenceRepository)
    {
        $this->epresenceRepository = $epresenceRepository;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:IN,OUT',
            'waktu' => 'required|date_format:Y-m-d H:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userId = Auth::id();

        $result = $this->epresenceRepository->createPresence($request->all(), $userId);

        if ($result['status'] === 'error') {
            return response()->json([
                'message' => $result['message'],
            ], 409);
        }

        return response()->json([
            'message' => 'Absensi berhasil dicatat',
            'data' => $result['data'],
        ], 201);
    }

     public function approve(Epresence $epresence)
    {
        $approverId = Auth::id();

        $result = $this->epresenceRepository->approve($epresence->id, $approverId);

        if ($result['status'] === 'error') {
            return response()->json(['message' => $result['message']], $result['code']);
        }

        return response()->json([
            'message' => 'Presensi berhasil disetujui.',
            'data' => $result['data'],
        ]);
    }

    public function index()
    {
        $data = $this->epresenceRepository->getRecapData();

        return response()->json([
            'message' => 'Success get data',
            'data' => $data
        ]);
    }
}