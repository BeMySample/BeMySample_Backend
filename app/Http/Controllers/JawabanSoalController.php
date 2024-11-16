<?php

namespace App\Http\Controllers;

use App\Models\JawabanSoal;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class JawabanSoalController extends Controller
{

    public function index()
    {
        $jawabanSoal = JawabanSoal::all();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dimuat',
            'data' => $jawabanSoal
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'responden_id' => 'required|integer',
                'jawaban' => 'required|string',
            ]);

            $jawabanSoal = JawabanSoal::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Dibuat',
                'data' => $jawabanSoal
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
        $jawabanSoal = JawabanSoal::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $jawabanSoal
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $jawabanSoal = JawabanSoal::findOrFail($id);

            $validated = $request->validate([
                'responden_id' => 'sometimes|integer',
                'jawaban' => 'sometimes|string',
            ]);

            $jawabanSoal->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Diupdate',
                'data' => $jawabanSoal
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ], 422);
        }
    }

    public function destroy($id)
    {
        $jawabanSoal = JawabanSoal::findOrFail($id);
        $jawabanSoal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus'
        ]);
    }
}
