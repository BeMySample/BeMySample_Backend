<?php

namespace App\Http\Controllers;

use App\Models\SoalType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SoalTypeController extends Controller
{
    public function index()
    {
        $soalType = SoalType::all();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dimuat',
            'data' => $soalType
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'icon' => 'required|string',
                'type' => 'required|string',
            ]);

            $soalType = SoalType::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Dibuat',
                'data' => $soalType
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ], 422);
        }
    }

    public function show($id)
    {
        $soalType = SoalType::find($id);

        if (!$soalType) {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $soalType
        ]);
    }

    public function update(Request $request, $id)
    {
        $soalType = SoalType::find($id);

        if (!$soalType) {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'icon' => 'required|string',
            'type' => 'required|string',
        ]);

        $soalType->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Diperbarui',
            'data' => $soalType
        ]);
    }

    public function destroy($id)
    {
        $soalType = SoalType::find($id);

        if (!$soalType) {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }

        $soalType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus'
        ]);
    }
}
