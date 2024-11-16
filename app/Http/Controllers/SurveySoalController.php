<?php

namespace App\Http\Controllers;

use App\Models\SurveySoal;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SurveySoalController extends Controller
{

    public function index()
    {
        $surveySoal = SurveySoal::all();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dimuat',
            'data' => $surveySoal
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'survey_id' => 'required|integer',
                'heading' => 'required|string',
                'desc' => 'required|string',
                'tipe_soal' => 'required|integer',
                'jawaban' => 'required|integer',
            ]);

            $surveySoal = SurveySoal::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Dibuat',
                'data' => $surveySoal
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
        $surveySoal = SurveySoal::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $surveySoal
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $surveySoal = SurveySoal::findOrFail($id);

            $validated = $request->validate([
                'survey_id' => 'required|integer',
                'heading' => 'required|string',
                'desc' => 'required|string',
                'tipe_soal' => 'required|integer',
                'jawaban' => 'required|integer',
            ]);

            $surveySoal->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Diupdate',
                'data' => $surveySoal
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
        $surveySoal = SurveySoal::findOrFail($id);
        $surveySoal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus'
        ]);
    }
}
