@extends('layouts.mobile')

@section('title', 'Mot de passe oublié')
@section('header_title', 'Récupération')

@section('content')
<div class="flex flex-col items-center mt-6 mb-6">
    <div class="auth-logo" style="font-size: 3rem; margin-bottom: 1rem;">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
    </div>
    <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">Mot de passe oublié</h2>
    <p class="text-muted text-center" style="font-size: 0.875rem; margin-bottom: 2rem;">Saisissez votre e-mail pour réinitialiser votre mot de passe via Firebase.</p>
</div>

@if (session('status'))
    <div style="background: var(--primary-light); color: var(--primary); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--primary); margin-bottom: 1.5rem; font-size: 0.85rem; font-weight: 500;">
        {{ session('status') }}
    </div>
@endif

<form action="{{ route('password.email') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="email" class="form-label">Adresse Email</label>
        <input type="email" id="email" name="email" class="form-input" placeholder="vous@exemple.com" required autofocus style="padding: 1rem;">
        @error('email')
            <span style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary w-full mt-2" style="padding: 1rem; font-size: 1.125rem;">Envoyer le lien</button>
</form>

<p class="text-center mt-6" style="font-size: 0.875rem; color: var(--text-muted);">
    Retour à la <a href="{{ route('login') }}" style="font-weight: 600;">Connexion</a>
</p>
@endsection
