<?php

namespace App\Http\Controllers;

use App\Models\SurveyDesain;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SurveyDesainController extends Controller
{

    public function index()
    {
        $surveyDesain = surveyDesain::all();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dimuat',
            'data' => $surveyDesain
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                // 'background_img' => 'required|string',
                'background_img' => 'required|file|mimes:jpeg,png,jpg,gif|max:10240',
                'opacity' => 'required|string',
                'warna_latar' => 'required|string',
                'warna_tombol' => 'required|string',
                'warna_tombol_text' => 'required|string',
                'warna_text' => 'required|string',
            ]);
    
            if ($request->hasFile('background_img')) {
                $file = $request->file('background_img');
                $path = $file->store('background', 'public');
                $validated['background_img'] = $path; 
            }

            $surveyDesain = SurveyDesain::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Dibuat',
                'data' => $surveyDesain
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
        $surveyDesain = SurveyDesain::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $surveyDesain
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $surveyDesain = SurveyDesain::findOrFail($id);

            $validated = $request->validate([
                'background_img' => 'required|file|mimes:jpeg,png,jpg,gif|max:10240',
                'opacity' => 'required|string',
                'warna_latar' => 'required|string',
                'warna_tombol' => 'required|string',
                'warna_tombol_text' => 'required|string',
                'warna_text' => 'required|string',
            ]);

            $surveyDesain->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Diupdate',
                'data' => $surveyDesain
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
        $surveyDesain = SurveyDesain::findOrFail($id);
        $surveyDesain->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus'
        ]);
    }
}