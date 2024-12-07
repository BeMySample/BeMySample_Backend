<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite; 
 
class AuthController extends Controller
{
    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     if (!Auth::attempt($request->only('email', 'password'))) {
    //         return response()->json(['message' => 'Invalid login details'], 401);
    //     }

    //     $user = Auth::user();
    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type' => 'Bearer',
    //         'user' => [
    //             'name' => $user->nama_lengkap, 
    //             'avatar' => $user->avatar ?? 'https://default-avatar-url.com/avatar.png', 
    //         ],
    //     ]);
    // }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'name' => $user->nama_lengkap,
                'avatar' => $user->avatar ?? 'https://default-avatar-url.com/avatar.png',
            ],
        ]);
    }

    // public function logout(Request $request)
    // {
    //     $request->user()->tokens()->delete();

    //     return response()->json(['message' => 'Logged out successfully']);
    // }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully']);
    }
    
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    
public function handleGoogleCallback()
{
    try {
        $googleUser = \Laravel\Socialite\Facades\Socialite::driver('google')->stateless()->user();

        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'username' => $googleUser->getEmail(),
                'password' => \Illuminate\Support\Facades\Hash::make($googleUser->getId()),
                'nama_lengkap' => $googleUser->getName() ?? 'Anonymous',
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
            ]
        );

        \Illuminate\Support\Facades\Auth::login($user);

        $token = $user->createToken('auth_token')->plainTextToken;
    return redirect("http://localhost:3000/dashboard?token=$token&name=" . urlencode($user->nama_lengkap) . "&avatar=" . urlencode($user->avatar));
    } catch (\Exception $e) {
        \Log::error('Google Login Error: ' . $e->getMessage());
        return redirect('http://localhost:3000/login?error=login_failed');
    }
}



}