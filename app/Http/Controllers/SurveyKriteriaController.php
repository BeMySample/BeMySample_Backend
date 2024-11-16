<?php

namespace App\Http\Controllers;

use App\Models\SurveyKriteria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SurveyKriteriaController extends Controller
{

    public function index()
    {
        $surveyKriteria = SurveyKriteria::all();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dimuat',
            'data' => $surveyKriteria
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'gender_target' => 'required|string',
                'age_target' => 'required|string',
                'lokasi' => 'required|string',
                'hobi' => 'required|string',
                'pekerjaan' => 'required|string',
                'tempat_bekerja' => 'required|string',
                'responden_target' => 'required|integer',
                'poin_foreach' => 'required|integer',
            ]);

            $surveyKriteria = SurveyKriteria::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Dibuat',
                'data' => $surveyKriteria
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
        $surveyKriteria = SurveyKriteria::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $surveyKriteria
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $surveyKriteria = SurveyKriteria::findOrFail($id);

            $validated = $request->validate([
                'gender_target' => 'required|string',
                'age_target' => 'required|string',
                'lokasi' => 'required|string',
                'hobi' => 'required|string',
                'pekerjaan' => 'required|string',
                'tempat_bekerja' => 'required|string',
                'responden_target' => 'required|integer',
                'poin_foreach' => 'required|integer',
            ]);

            $surveyKriteria->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Diupdate',
                'data' => $surveyKriteria
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
        $surveyKriteria = SurveyKriteria::findOrFail($id);
        $surveyKriteria->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus'
        ]);
    }
}
