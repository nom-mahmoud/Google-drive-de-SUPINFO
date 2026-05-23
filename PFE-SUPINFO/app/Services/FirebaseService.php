<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    protected $apiKey;
    protected $projectId;

    public function __construct()
    {
        $this->apiKey = env('FIREBASE_API_KEY');
        $this->projectId = env('FIREBASE_PROJECT_ID');
    }

    /**
     * Create user in Firebase Auth if not exists
     */
    public function signUp($email, $password)
    {
        if (!$this->apiKey) {
            Log::error('Firebase API Key is not set in .env');
            return false;
        }

        $response = Http::post("https://identitytoolkit.googleapis.com/v1/accounts:signUp?key={$this->apiKey}", [
            'email' => $email,
            'password' => $password,
            'returnSecureToken' => true,
        ]);

        if ($response->failed()) {
            $error = $response->json()['error']['message'] ?? 'Unknown error';
            if ($error === 'EMAIL_EXISTS') {
                return true; // Already exists, which is fine
            }
            Log::error("Firebase SignUp failed for {$email}: {$error}");
            return false;
        }

        return true;
    }

    /**
     * Send Password Reset Email from Firebase
     */
    public function sendPasswordResetEmail($email)
    {
        if (!$this->apiKey) {
            Log::error('Firebase API Key is not set in .env');
            return false;
        }

        // First, ensure the account exists in Firebase Auth by doing a signup with a random password if needed
        $this->signUp($email, \Illuminate\Support\Str::random(16));

        $response = Http::post("https://identitytoolkit.googleapis.com/v1/accounts:sendOobCode?key={$this->apiKey}", [
            'requestType' => 'PASSWORD_RESET',
            'email' => $email,
        ]);

        if ($response->failed()) {
            $error = $response->json()['error']['message'] ?? 'Unknown error';
            Log::error("Firebase sendPasswordResetEmail failed for {$email}: {$error}");
            return false;
        }

        return true;
    }

    /**
     * Verify Password Reset Code (checks if valid and returns email)
     */
    public function verifyPasswordResetCode($oobCode)
    {
        if (!$this->apiKey) {
            return false;
        }

        $response = Http::post("https://identitytoolkit.googleapis.com/v1/accounts:resetPassword?key={$this->apiKey}", [
            'oobCode' => $oobCode,
        ]);

        if ($response->failed()) {
            $error = $response->json()['error']['message'] ?? 'Unknown error';
            Log::error("Firebase verifyPasswordResetCode failed: {$error}");
            return false;
        }

        return $response->json();
    }

    /**
     * Confirm password reset (changes the password on Firebase and returns email)
     */
    public function confirmPasswordReset($oobCode, $newPassword)
    {
        if (!$this->apiKey) {
            return false;
        }

        $response = Http::post("https://identitytoolkit.googleapis.com/v1/accounts:resetPassword?key={$this->apiKey}", [
            'oobCode' => $oobCode,
            'newPassword' => $newPassword,
        ]);

        if ($response->failed()) {
            $error = $response->json()['error']['message'] ?? 'Unknown error';
            Log::error("Firebase confirmPasswordReset failed: {$error}");
            return false;
        }

        return $response->json();
    }
}
