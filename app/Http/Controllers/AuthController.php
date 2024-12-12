<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Password;
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


    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     if (!Auth::attempt($request->only('email', 'password'))) {
    //         return response()->json(['message' => 'Invalid login details'], 401);
    //     }

    //     $request->session()->regenerate();

    //     $user = Auth::user();

    //     return response()->json([
    //         'message' => 'Login successful',
    //         'user' => [
    //             'nama_lengkap' => $user->nama_lengkap,
    //             'avatar' => $user->avatar ?? 'https://www.pngplay.com/wp-content/uploads/12/User-Avatar-Profile-Transparent-Free-PNG-Clip-Art.png',
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

    $user = Auth::user();

    // Tentukan metode login berdasarkan nilai google_id
    $loginMethod = $user->google_id === 'unknown' ? 'email' : 'google';

    // Generate auth token
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
        'user' => [
            'nama_lengkap' => $user->nama_lengkap,
            'avatar' => $user->avatar ?? 'https://default-avatar-url.com/avatar.png',
            'login_method' => $loginMethod, // Tentukan metode login
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
    // Logout dengan sesi
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json(['message' => 'Logged out successfully'], 200);
}

    
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function sendResetLink(Request $request)
{
    $request->validate([
        'email' => 'required|email',
    ]);

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

    public function handleGoogleCallback()
{
    try {
        $googleUser = \Laravel\Socialite\Facades\Socialite::driver('google')->stateless()->user();

        // Cari pengguna di database berdasarkan email
        $user = User::where('email', $googleUser->getEmail())->first();

        // Tentukan apakah google_id cocok
        $isAuth = $user && $user->google_id === $googleUser->getId();

        // Tentukan apakah user sudah terdaftar berdasarkan email
        $isRegistered = (bool) $user;

        // Jika user baru, buat user baru
        if (!$user) {
            $user = User::create([
                'email' => $googleUser->getEmail(),
                'username' => $googleUser->getEmail(),
                'password' => \Illuminate\Support\Facades\Hash::make($googleUser->getId()),
                'nama_lengkap' => $googleUser->getName() ?? 'Anonymous',
                'google_id' => $googleUser->getId(), // Hanya set google_id untuk user baru
                'avatar' => $googleUser->getAvatar(),
            ]);
        } elseif (!$isAuth) {
            // Jika google_id tidak cocok, user tidak terautentikasi
            return redirect('http://localhost:3000/login?error=unauthorized');
        }

        // Login user
        \Illuminate\Support\Facades\Auth::login($user);

        // Generate auth token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Redirect ke frontend dengan token, status registrasi, otentikasi, dan ID
        return redirect("http://localhost:3000/login?token=$token&isRegistered=" . ($isRegistered ? 'true' : 'false') . "&isAuth=" . ($isAuth ? 'true' : 'false') . "&id={$user->id}");
    } catch (\Exception $e) {
        \Log::error('Google Login Error: ' . $e->getMessage());
        return redirect('http://localhost:3000/login?error=login_failed');
    }
}

    



}