<?php

namespace App\Http\Controllers;

use App\Models\KontribusiExplore;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class KontribusiExploreController extends Controller
{
    public function index()
    {
        $kontribusiExplores = KontribusiExplore::all();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dimuat',
            'data' => $kontribusiExplores
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_author' => 'required|integer',
                'thumbnail' => 'required|string',
                'judul' => 'required|string',
                'coin' => 'required|integer',
                'kriteria' => 'required|string',
            ]);

            $kontribusiExplore = KontribusiExplore::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Dibuat',
                'data' => $kontribusiExplore
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
        $kontribusiExplore = KontribusiExplore::find($id);

        if (!$kontribusiExplore) {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $kontribusiExplore
        ]);
    }

    public function update(Request $request, $id)
    {
        $kontribusiExplore = KontribusiExplore::find($id);

        if (!$kontribusiExplore) {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'id_author' => 'required|integer',
            'thumbnail' => 'required|string',
            'judul' => 'required|string',
            'coin' => 'required|integer',
            'kriteria' => 'required|string',
        ]);

        $kontribusiExplore->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Diperbarui',
            'data' => $kontribusiExplore
        ]);
    }

    public function destroy($id)
    {
        $kontribusiExplore = KontribusiExplore::find($id);

        if (!$kontribusiExplore) {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }

        $kontribusiExplore->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus'
        ]);
    }
}
