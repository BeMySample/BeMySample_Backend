<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    private $baseUrl = "http://localhost:8000";
    public $placeholder = "https://cdn.pixabay.com/photo/2016/08/08/09/17/avatar-1577909_960_720.png";

    public function index()
    {
        $user = User::select('username', 'nama_lengkap')->get();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dimuat',
            'data' => $user
        ]);
    }

    // private function handleAvatarUpload($avatarFile = null)
    // {
    //     if ($avatarFile) {
    //         $avatarPath = $avatarFile->store('avatar', 'public');
    //         return $this->baseUrl . Storage::url($avatarPath);
    //     }

    //     return $this->placeholder;
    // }

    private function handleAvatarUpload($avatarFile = null)
    {
        if ($avatarFile) {
            $avatarPath = $avatarFile->store('avatar', 'public');
            return Storage::url($avatarPath);
        }

        return $this->placeholder;
    }

    public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'username' => 'required|unique:user,username',
            'nama_lengkap' => 'required|string',
            'email' => 'required|email|unique:user,email',
            'google_id' => 'required|string',
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

        // Validasi khusus avatar (file atau URL)
        if ($request->hasFile('avatar')) {
            $request->validate([
                'avatar' => 'file|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            $avatarUrl = $this->handleAvatarUpload($request->file('avatar'));
        } elseif ($request->input('avatar')) {
            $request->validate([
                'avatar' => 'string|url',
            ]);
            $avatarUrl = $request->input('avatar');
        } else {
            $avatarUrl = null;
        }

        // Buat user baru
        $user = User::create([
            'username' => $validated['username'],
            'nama_lengkap' => $validated['nama_lengkap'],
            'email' => $validated['email'],
            'google_id' => $validated['google_id'],
            'avatar' => $avatarUrl,
            'password' => Hash::make($validated['password']),
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
            'umur' => $validated['umur'] ?? null,
            'lokasi' => $validated['lokasi'] ?? null,
            'minat' => $validated['minat'] ?? null,
            'institusi' => $validated['institusi'] ?? null,
            'poin_saya' => $validated['poin_saya'] ?? 0,
            'pekerjaan' => $validated['pekerjaan'] ?? null,
        ]);

        return response()->json($user, 201);

    } catch (ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => $e->errors(),
        ], 422);
    }
}

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'avatar' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $avatarUrl = $this->handleAvatarUpload($request->file('avatar'));

        return response()->json([
            'success' => true,
            'message' => 'Image uploaded successfully',
            'avatar_url' => $avatarUrl,
        ]);
    }

    // public function update(Request $request, $id)
    // {
    //     $user = User::findOrFail($id);

    //     $validated = $request->validate([
    //         'username' => ['required', Rule::unique('user')->ignore($user->id)],
    //         'status' => 'required|string',
    //         'nama_lengkap' => 'required|string',
    //         'email' => ['required', 'email', Rule::unique('user')->ignore($user->id)],
    //         'google_id' => 'required|string',
    //         'avatar' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
    //         'password' => 'nullable|min:6',
    //         'tanggal_lahir' => 'nullable|date',
    //         'jenis_kelamin' => 'nullable|in:laki-laki,perempuan',
    //         'umur' => 'nullable|integer',
    //         'lokasi' => 'nullable|string',
    //         'minat' => 'nullable|string',
    //         'institusi' => 'nullable|string',
    //         'poin_saya' => 'nullable|integer',
    //         'pekerjaan' => 'nullable|string',
    //     ]);

    //     if ($request->hasFile('avatar')) {
    //         if ($user->avatar) {
    //             $currentAvatarPath = str_replace($this->baseUrl . '/storage/', '', $user->avatar);
    //             Storage::disk('public')->delete($currentAvatarPath);
    //         }
    //         $validated['avatar'] = $this->handleAvatarUpload($request->file('avatar'));
    //     }

    //     $user->update(array_filter($validated));
    //     return response()->json($user);
    // }

    
    public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $validated = $request->validate([
        'username' => ['required', Rule::unique('user')->ignore($user->id)],
        'nama_lengkap' => 'required|string',
        'email' => ['required', 'email', Rule::unique('user')->ignore($user->id)],
        'google_id' => 'required|string',
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

    // Validasi khusus untuk avatar (file atau URL)
    if ($request->hasFile('avatar')) {
        // Hapus avatar lama jika ada
        if ($user->avatar && $user->avatar != $this->placeholder) {
            $currentAvatarPath = str_replace($this->baseUrl . '/storage/', '', $user->avatar);
            Storage::disk('public')->delete($currentAvatarPath);
        }

        // Upload avatar baru
        $avatarPath = $request->file('avatar')->store('avatar', 'public');
        $validated['avatar'] = $this->baseUrl . Storage::url($avatarPath);
    } elseif ($request->input('avatar')) {
        // Jika avatar adalah URL, validasi input
        $request->validate([
            'avatar' => 'string|url',
        ]);
        $validated['avatar'] = $request->input('avatar');
    } else {
        // Gunakan avatar lama atau placeholder
        $validated['avatar'] = $user->avatar ?? $this->placeholder;
    }

    // Perbarui data user
    $user->update(array_filter($validated));

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
