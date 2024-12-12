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
                'avatar' => 'nullable|string',
                'password' => 'required|min:6',
                'tanggal_lahir' => 'nullable|date',
                'jenis_kelamin' => 'nullable|in:laki-laki,perempuan',
                'umur' => 'nullable|integer',
                'lokasi' => 'nullable|string',
                'minat' => 'nullable|string',
                'institusi' => 'nullable|string',
                'poin_saya' => 'nullable|integer',
                'pekerjaan' => 'nullable|string',
                // 'profilepic' => 'nullable|string',
            ]);

            $avatarPath = $request->file('avatar')
                ? $request->file('avatar')->store('avatar', 'public')
                : null;

            $user = User::create([
                'username' => $validated['username'],
                'nama_lengkap' => $validated['nama_lengkap'],
                'status' => $validated['status'],
                'email' => $validated['email'],
                'google_id' => $validated['google_id'],
                // 'avatar' => $validated['avatar'],
                'profilepic' => $avatarPath ? Storage::url($avatarPath) : null,
                'password' => Hash::make($validated['password']),
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'umur' => $validated['umur'],
                'lokasi' => $validated['lokasi'],
                'minat' => $validated['minat'],
                'institusi' => $validated['institusi'],
                'poin_saya' => $validated['poin_saya'] ?? 0,
                'pekerjaan' => $validated['pekerjaan'],
                // 'profilepic' => $validated['profilepic'],
            ]);

            // if ($user) {

            //     return response()->json([
            //         'success' => true,
            //         'message' => 'Data Berhasil Dibuat'
            //     ], 201);

            // } else {
                
            //     return response()->json([
            //         'success' => true,
            //         'message' => 'Data Gagal Dibuat'
            //     ], 400);

            // }

            return response()->json($user, status: 201, );

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ], 422);
        }
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function showCurrentUser(Request $request)
{
    $user = $request->user();
    if (!$user) {
        \Log::info('User not authenticated.');
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    \Log::info('Authenticated user:', ['user' => $user->toArray()]);
    return response()->json([
        'success' => true,
        'data' => $user
    ]);
}


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => ['required', Rule::unique('user')->ignore($user->id)],
            'status' => 'required|string',
            'nama_lengkap' => 'required|string',
            'email' => ['required', 'email', Rule::unique('user')->ignore($user->id)],
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
            // 'profilepic' => 'nullable|string',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $user->avatar));
            }
            $avatarPath = $request->file('avatar')->store('avatar', 'public');
            $validated['avatar'] = Storage::url($avatarPath);
        }

        $user->update(array_filter($validated)); 
        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
