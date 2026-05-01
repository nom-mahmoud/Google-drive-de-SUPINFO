<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (\Exception $e) {
            return redirect(env('FRONTEND_URL', 'http://localhost:5173') . '/login?error=' . urlencode($e->getMessage()));
        }

        $user = User::updateOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'oauth_provider' => $provider,
                'oauth_id' => $socialUser->getId(),
            ]
        );

        $token = $user->createToken('auth_token')->plainTextToken;

        return redirect(env('FRONTEND_URL', 'http://localhost:5173') . '/oauth/callback?token=' . $token);
    }
}
