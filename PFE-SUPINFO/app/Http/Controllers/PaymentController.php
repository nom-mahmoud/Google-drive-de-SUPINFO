<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    private function getStripeSecretKey()
    {
        return env('STRIPE_SECRET');
    }

    public function checkout(Request $request)
    {
        $plan = $request->input('plan');
        
        $priceCents = 499;
        $limitName = '100 Go';
        
        if ($plan === 'Premium') {
            $priceCents = 999;
            $limitName = '500 Go';
        } elseif ($plan === 'Business') {
            $priceCents = 1999;
            $limitName = '2 To';
        } elseif ($plan !== 'Pro') {
            return back()->withErrors(['plan' => 'Plan invalide.']);
        }

        try {
            $response = Http::asForm()->withHeaders([
                'Authorization' => 'Bearer ' . $this->getStripeSecretKey()
            ])->post('https://api.stripe.com/v1/checkout/sessions', [
                'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}&plan=' . $plan,
                'cancel_url' => route('settings'),
                'mode' => 'payment',
                'line_items[0][price_data][currency]' => 'eur',
                'line_items[0][price_data][product_data][name]' => 'Abonnement SUPFile ' . $plan . ' (' . $limitName . ')',
                'line_items[0][price_data][unit_amount]' => $priceCents,
                'line_items[0][quantity]' => 1,
            ]);

            if ($response->successful()) {
                $session = $response->json();
                return redirect($session['url']);
            }

            return back()->withErrors(['error' => 'Erreur Stripe : ' . ($response->json()['error']['message'] ?? 'Impossible de créer la session de paiement.')]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur de connexion avec Stripe : ' . $e->getMessage()]);
        }
    }

    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');
        $plan = $request->query('plan');

        if (!$sessionId || !$plan) {
            return redirect()->route('settings')->withErrors(['error' => 'Session de paiement invalide.']);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getStripeSecretKey()
            ])->get('https://api.stripe.com/v1/checkout/sessions/' . $sessionId);

            if ($response->successful() && $response->json()['payment_status'] === 'paid') {
                $user = Auth::user();
                
                $user->plan = $plan;
                if ($plan === 'Pro') {
                    $user->storage_limit = 100 * 1024 * 1024 * 1024; // 100 GB
                } elseif ($plan === 'Premium') {
                    $user->storage_limit = 500 * 1024 * 1024 * 1024; // 500 GB
                } elseif ($plan === 'Business') {
                    $user->storage_limit = 2000 * 1024 * 1024 * 1024; // 2 TB
                }

                $user->save();

                return redirect()->route('settings')->with('success', "💳 Félicitations ! Votre paiement Stripe a été validé. Votre espace disque est maintenant de " . ($plan === 'Pro' ? '100 Go' : ($plan === 'Premium' ? '500 Go' : '2 To')) . ".");
            }

            return redirect()->route('settings')->withErrors(['error' => 'Le paiement n\'a pas pu être validé.']);
        } catch (\Exception $e) {
            return redirect()->route('settings')->withErrors(['error' => 'Une erreur est survenue lors de la validation du paiement : ' . $e->getMessage()]);
        }
    }
}
