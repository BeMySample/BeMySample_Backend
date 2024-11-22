<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite; // Tambahkan ini untuk Socialite
 
class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    // Redirect ke halaman login Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Callback setelah login Google berhasil
    public function handleGoogleCallback()
{
    try {
        $googleUser = \Laravel\Socialite\Facades\Socialite::driver('google')->stateless()->user();

        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'username' => $googleUser->getEmail(),
                'nama_lengkap' => $googleUser->getName() ?? 'Anonymous',
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
            ]
        );

        \Illuminate\Support\Facades\Auth::login($user);

        $token = $user->createToken('auth_token')->plainTextToken;

        // Redirect to frontend React with user data in query string
        return redirect("http://localhost:3000/dashboard?token=$token&name=" . urlencode($user->nama_lengkap) . "&avatar=" . urlencode($user->avatar));
    } catch (\Exception $e) {
        \Log::error('Google Login Error: ' . $e->getMessage());
        return redirect('http://localhost:3000/login?error=login_failed');
    }
}


}