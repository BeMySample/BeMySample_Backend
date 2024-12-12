<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    private $baseUrl = "http://localhost:3000/";

    public function index()
    {
        $user = User::select('username', 'nama_lengkap')->get();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dimuat',
            'data' => $user
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|unique:user,username',
                'status' => 'required|string',
                'nama_lengkap' => 'required|string',
                'email' => 'required|email|unique:user,email',
                'google_id' => 'required|string',
                'avatar' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
                'password' => 'required|min:6',
                'tanggal_lahir' => 'nullable|date',
                'jenis_kelamin' => 'nullable|in:laki-laki,perempuan',
                'umur' => 'nullable|integer',
                'lokasi' => 'nullable|string',
                'minat' => 'nullable|string',
                'institusi' => 'nullable|string',
                'poin_saya' => 'nullable|integer',
                'pekerjaan' => 'nullable|string',
            ]);

            $avatarPath = $request->file('avatar')
                ? $request->file('avatar')->store('avatar', 'public')
                : null;

            $avatarUrl = $avatarPath ? $this->baseUrl . Storage::url($avatarPath) : null;

            $user = User::create([
                'username' => $validated['username'],
                'nama_lengkap' => $validated['nama_lengkap'],
                'status' => $validated['status'],
                'email' => $validated['email'],
                'google_id' => $validated['google_id'],
                'avatar' => $avatarUrl,
                'password' => Hash::make($validated['password']),
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'umur' => $validated['umur'],
                'lokasi' => $validated['lokasi'],
                'minat' => $validated['minat'],
                'institusi' => $validated['institusi'],
                'poin_saya' => $validated['poin_saya'] ?? 0,
                'pekerjaan' => $validated['pekerjaan'],
            ]);

            return response()->json($user, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ], 422);
        }
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'avatar' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $avatarPath = $request->file('avatar')->store('avatar', 'public');
        $avatarUrl = $this->baseUrl . Storage::url($avatarPath);

        return response()->json([
            'success' => true,
            'message' => 'Image uploaded successfully',
            'avatar_url' => $avatarUrl,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => 'required|unique:user,username',
            'status' => 'required|string',
            'nama_lengkap' => 'required|string',
            'email' => 'required|email|unique:user,email',
            'google_id' => 'required|string',
            'avatar' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|min:6',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:laki-laki,perempuan',
            'umur' => 'nullable|integer',
            'lokasi' => 'nullable|string',
            'minat' => 'nullable|string',
            'institusi' => 'nullable|string',
            'poin_saya' => 'nullable|integer',
            'pekerjaan' => 'nullable|string',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                $currentAvatarPath = str_replace($this->baseUrl . '/storage/', '', $user->avatar);
                Storage::disk('public')->delete($currentAvatarPath);
            }
            $avatarPath = $request->file('avatar')->store('avatar', 'public');
            $validated['avatar'] = $this->baseUrl . Storage::url($avatarPath);
        }

        $user->update(array_filter($validated));
        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->avatar) {
            $currentAvatarPath = str_replace($this->baseUrl . '/storage/', '', $user->avatar);
            Storage::disk('public')->delete($currentAvatarPath);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
