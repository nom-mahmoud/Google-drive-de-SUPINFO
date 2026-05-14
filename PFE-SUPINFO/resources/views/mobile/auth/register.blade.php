@extends('layouts.mobile')

@section('title', 'Inscription')

@section('header_title', 'Créer un compte')
@section('header_actions')
    <a href="{{ route('login') ?? '#' }}" style="font-size: 0.875rem; font-weight: 500;">Connexion</a>
@endsection

@section('content')
<div class="mt-4 mb-6">
    <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">Commencez ici</h2>
    <p class="text-muted" style="font-size: 0.875rem;">30 Go de stockage cloud premium gratuit.</p>
</div>

<form action="{{ route('register.post') ?? '#' }}" method="POST">
    @csrf
    
    <div class="form-group">
        <label for="firstname" class="form-label">Prénom</label>
        <input type="text" id="firstname" name="firstname" class="form-input" placeholder="Jean" required style="padding: 1rem;">
    </div>
    
    <div class="form-group">
        <label for="lastname" class="form-label">Nom</label>
        <input type="text" id="lastname" name="lastname" class="form-input" placeholder="Dupont" required style="padding: 1rem;">
    </div>

    <div class="form-group">
        <label for="email" class="form-label">Adresse Email</label>
        <input type="email" id="email" name="email" class="form-input" placeholder="vous@exemple.com" required style="padding: 1rem;">
    </div>
    
    <div class="form-group">
        <label for="password" class="form-label">Mot de passe</label>
        <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required style="padding: 1rem;">
    </div>

    <div class="form-group">
        <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="••••••••" required style="padding: 1rem;">
    </div>

    <button type="submit" class="btn btn-primary w-full mt-4" style="padding: 1rem; font-size: 1.125rem;">M'inscrire maintenant</button>
</form>

<div class="divider">ou</div>

<div class="flex flex-col gap-3">
    <a href="{{ route('oauth.redirect', 'google') }}" class="btn btn-outline w-full flex items-center justify-center gap-2" style="padding: 1rem;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
        S'inscrire avec Google
    </a>
    <a href="{{ route('oauth.redirect', 'github') }}" class="btn btn-outline w-full flex items-center justify-center gap-2" style="padding: 1rem;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path></svg>
        S'inscrire avec GitHub
    </a>
</div>

<div class="mt-6 text-center text-muted" style="font-size: 0.75rem;">
    En vous inscrivant, vous acceptez nos Conditions d'utilisation et notre Politique de confidentialité.
</div>
@endsection
