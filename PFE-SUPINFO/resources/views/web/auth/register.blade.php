@extends('layouts.web')

@section('title', 'Inscription')

@section('content')
<div class="auth-wrapper" style="min-height: calc(100vh - 80px);">
    <div class="auth-container bento-card" style="margin: 0 auto; max-width: 500px;">
        <div class="auth-header">
            <h1 class="auth-logo">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                SUPFile
            </h1>
            <p class="auth-subtitle">Rejoignez-nous et profitez de 30 Go de stockage cloud premium gratuit.</p>
        </div>

        <form action="{{ route('register.post') ?? '#' }}" method="POST">
            @csrf
            
            <div class="flex gap-4" style="margin-bottom: 1.5rem;">
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <label for="firstname" class="form-label">Prénom</label>
                    <input type="text" id="firstname" name="firstname" class="form-input" placeholder="Jean" required>
                </div>
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <label for="lastname" class="form-label">Nom</label>
                    <input type="text" id="lastname" name="lastname" class="form-input" placeholder="Dupont" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Adresse Email</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="vous@exemple.com" required>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary w-full mt-4">Créer mon compte</button>
        </form>

        <p class="text-center mt-4" style="font-size: 0.875rem; color: var(--text-muted);">
            Vous avez déjà un compte ? <a href="{{ route('login') ?? '#' }}" style="font-weight: 600;">Se connecter</a>
        </p>
    </div>
</div>
@endsection
