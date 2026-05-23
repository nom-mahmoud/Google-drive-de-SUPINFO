@extends('layouts.web')

@section('title', 'Mot de passe oublié')

@section('content')
<div class="auth-wrapper" style="min-height: calc(100vh - 80px);">
    <div class="auth-container bento-card" style="margin: 0 auto; max-width: 420px;">
        <div class="auth-header">
            <h1 class="auth-logo">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                SUPFile
            </h1>
            <p class="auth-subtitle">Saisissez votre e-mail pour recevoir un lien de réinitialisation via Firebase.</p>
        </div>

        @if (session('status'))
            <div style="background: var(--primary-light); color: var(--primary); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--primary); margin-bottom: 1.5rem; font-size: 0.9rem; font-weight: 500;">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email" class="form-label">Adresse Email</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="vous@exemple.com" required autofocus>
                @error('email')
                    <span style="color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-full mt-4">Envoyer le lien de réinitialisation</button>
        </form>

        <p class="text-center mt-4" style="font-size: 0.875rem; color: var(--text-muted);">
            Retour à la <a href="{{ route('login') }}" style="font-weight: 600;">Connexion</a>
        </p>
    </div>
</div>
@endsection
