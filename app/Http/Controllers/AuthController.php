<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Password;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class AuthController extends Controller
{
    // User Login
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

        $loginMethod = $user->google_id === 'unknown' ? 'email' : 'google';

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'nama_lengkap' => $user->nama_lengkap,
                'avatar' => $user->avatar ?? 'https://cdn.pixabay.com/photo/2016/08/08/09/17/avatar-1577909_960_720.png',
                'login_method' => $loginMethod,
            ],
        ]);
    }

    // User Logout
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    // Redirect to Google Authentication
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle Google Authentication Callback
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Find user by email
            $user = User::where('email', $googleUser->getEmail())->first();

            $isAuth = $user && $user->google_id === $googleUser->getId();
            $isRegistered = (bool) $user;

            // Create new user if not registered
            if (!$user) {
                $user = User::create([
                    'email' => $googleUser->getEmail(),
                    'username' => $googleUser->getEmail(),
                    'password' => Hash::make($googleUser->getId()),
                    'nama_lengkap' => $googleUser->getName() ?? 'Anonymous',
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            } elseif (!$isAuth) {
                return redirect('http://localhost:3000/login?error=unauthorized');
            }

            Auth::login($user);

            $token = $user->createToken('auth_token')->plainTextToken;

            return redirect("http://localhost:3000/login?token=$token&isRegistered=" . ($isRegistered ? 'true' : 'false') . "&isAuth=" . ($isAuth ? 'true' : 'false') . "&id={$user->id}");
        } catch (\Exception $e) {
            \Log::error('Google Login Error: ' . $e->getMessage());
            return redirect('http://localhost:3000/login?error=login_failed');
        }
    }
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && $user->google_id !== 'unknown') {
            return response()->json([
                'message' => 'Password reset is not allowed.',
            ], 403);
        }

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Password reset link sent to your email.'], 200);
        } else {
            return response()->json(['message' => 'Unable to send reset link. Please check the email address.'], 400);
        }
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if ($user && $user->google_id !== 'unknown') {
            return response()->json([
                'message' => 'Password reset is not allowed.',
            ], 403);
        }
    
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );
    
        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password reset successfully.'], 200);
        } else {
            return response()->json(['message' => 'Invalid token or email.'], 400);
        }
    }
    
}
