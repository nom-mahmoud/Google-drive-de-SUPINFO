@extends('layouts.mobile')

@section('title', 'Connexion')

@section('header_title', 'Connexion')

@section('content')
<div class="flex flex-col items-center mt-6 mb-6">
    <div class="auth-logo" style="font-size: 3rem; margin-bottom: 1rem;">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
    </div>
    <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">Bienvenue !</h2>
    <p class="text-muted text-center" style="font-size: 0.875rem; margin-bottom: 2rem;">Accédez à votre espace de stockage cloud premium.</p>
</div>

<form action="{{ route('login.post') ?? '#' }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="email" class="form-label">Adresse Email</label>
        <input type="email" id="email" name="email" class="form-input" placeholder="vous@exemple.com" required style="padding: 1rem;">
    </div>
    
    <div class="form-group">
        <div class="flex items-center justify-between" style="margin-bottom: 0.5rem;">
            <label for="password" class="form-label" style="margin-bottom: 0;">Mot de passe</label>
            <a href="{{ route('password.request') }}" style="font-size: 0.875rem;">Oublié ?</a>
        </div>
        <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required style="padding: 1rem;">
    </div>

    <button type="submit" class="btn btn-primary w-full mt-2" style="padding: 1rem; font-size: 1.125rem;">Se connecter</button>
</form>

<div class="divider">ou</div>

<div class="flex flex-col gap-3">
    <a href="{{ route('oauth.redirect', 'google') }}" class="btn btn-outline w-full flex items-center justify-center gap-2" style="padding: 1rem;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
        Continuer avec Google
    </a>
    <a href="{{ route('oauth.redirect', 'github') }}" class="btn btn-outline w-full flex items-center justify-center gap-2" style="padding: 1rem;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path></svg>
        Continuer avec GitHub
    </a>
</div>

<p class="text-center mt-6" style="font-size: 0.875rem; color: var(--text-muted);">
    Pas de compte ? <a href="{{ route('register') ?? '#' }}" style="font-weight: 600;">S'inscrire</a>
</p>
@endsection
