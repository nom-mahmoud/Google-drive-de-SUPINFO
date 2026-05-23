<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ForgotPasswordController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    private function isMobile(Request $request)
    {
        return preg_match('/Mobile|Android|BlackBerry|iPhone|Windows Phone/i', $request->header('User-Agent'));
    }

    /**
     * Show the forgot password link request form.
     */
    public function showLinkRequestForm(Request $request)
    {
        $viewPath = $this->isMobile($request) ? 'mobile.auth.forgot-password' : 'web.auth.forgot-password';
        return view($viewPath);
    }

    /**
     * Send password reset link using Firebase.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $success = $this->firebaseService->sendPasswordResetEmail($request->email);

        if ($success) {
            return back()->with('status', 'Un e-mail de réinitialisation de mot de passe a été envoyé via Firebase.');
        }

        return back()->withErrors(['email' => 'Une erreur est survenue lors de la communication avec Firebase. Veuillez réessayer.']);
    }

    /**
     * Show the reset form after clicking the email link.
     */
    public function showResetForm(Request $request)
    {
        $oobCode = $request->get('oobCode');

        if (!$oobCode) {
            return redirect()->route('login')->withErrors(['error' => 'Code de réinitialisation manquant.']);
        }

        // Verify that the code is valid on Firebase
        $result = $this->firebaseService->verifyPasswordResetCode($oobCode);

        if (!$result) {
            return redirect()->route('login')->withErrors(['error' => 'Le lien de réinitialisation est invalide ou a expiré.']);
        }

        $email = $result['email'];

        $viewPath = $this->isMobile($request) ? 'mobile.auth.reset-password' : 'web.auth.reset-password';

        return view($viewPath, compact('oobCode', 'email'));
    }

    /**
     * Execute password reset.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'oobCode' => 'required|string',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Complete password reset in Firebase
        $result = $this->firebaseService->confirmPasswordReset($request->oobCode, $request->password);

        if (!$result) {
            return back()->withErrors(['password' => 'Échec de la réinitialisation de mot de passe Firebase. Le lien est peut-être expiré.']);
        }

        $email = $result['email'];

        // Synchronize password locally in Postgres
        $user = User::where('email', $email)->first();

        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return redirect()->route('login')->with('success', 'Votre mot de passe a été réinitialisé avec succès via Firebase !');
    }
}
