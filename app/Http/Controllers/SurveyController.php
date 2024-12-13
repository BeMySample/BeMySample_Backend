<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SurveyController extends Controller
{
    public function index()
    {
        $survey = Survey::all();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dimuat',
            'data' => $survey
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer',
                'judul_survey' => 'required|string',
                'deskripsi_survey' => 'required|string',
                'thumbnail' => 'required|file|mimes:jpeg,png,jpg,gif|max:10240',
                'status' => 'required|string',
                'responden_now' => 'required|integer',
                'coin_allocated' => 'required|integer',
                'coin_used' => 'required|integer',
                'jumlah_soal' => 'required|integer',
                'desainAttr' => 'required|integer',
                'kriteria' => 'required|integer',
            ]);
    
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $path = $file->store('thumbnail', 'public');
                $validated['thumbnail'] = $path; 
            }
    
            $survey = Survey::create($validated);
    
            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Dibuat',
                'data' => $survey
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
        $survey = Survey::find($id);

        if (!$survey) {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $survey
        ]);
    }

    public function update(Request $request, $id)
    {
        $survey = Survey::find($id);

        if (!$survey) {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'user_id'=> 'required|integer',
            'judul_survey'=> 'required|string',
            'deskripsi_survey'=> 'required|string',
            'thumbnail' => 'required|file|mimes:jpeg,png,jpg,gif|max:10240',
            'status'=> 'required|string',
            'responden_now'=> 'required|integer',
            'coin_allocated'=> 'required|integer',
            'coin_used'=> 'required|integer',
            'jumlah_soal'=> 'required|integer',
            'desainAttr'=> 'required|integer',
            'kriteria'=> 'required|integer'
        ]);

        $survey->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Diperbarui',
            'data' => $survey
        ]);
    }

    public function destroy($id)
    {
        $survey = Survey::find($id);

        if (!$survey) {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }

        $survey->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus'
        ]);
    }
}