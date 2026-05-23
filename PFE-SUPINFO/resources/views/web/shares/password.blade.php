@extends('layouts.web')
@section('title', 'Accès Sécurisé')

@section('content')
<div class="auth-wrapper" style="min-height: 60vh;">
    <div class="auth-container">
        <div class="bento-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <span>Lien Sécurisé</span>
                </div>
                <p class="auth-subtitle">Ce partage est protégé par un mot de passe.</p>
            </div>

            <form action="{{ route('shares.verify', $token) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Saisissez le mot de passe</label>
                    <input type="password" name="password" class="form-input" placeholder="••••••••" required autofocus>
                    @error('password')
                        <span style="color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-full">Valider et accéder</button>
            </form>
        </div>
    </div>
</div>
@endsection
