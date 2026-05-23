@extends('layouts.web')

@section('title', 'Réinitialiser le mot de passe')

@section('content')
<div class="auth-wrapper" style="min-height: calc(100vh - 80px);">
    <div class="auth-container bento-card" style="margin: 0 auto; max-width: 420px;">
        <div class="auth-header">
            <h1 class="auth-logo">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                SUPFile
            </h1>
            <p class="auth-subtitle">Réinitialisation pour <strong>{{ $email }}</strong></p>
        </div>

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="oobCode" value="{{ $oobCode }}">

            <div class="form-group">
                <label for="password" class="form-label">Nouveau mot de passe</label>
                <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required autofocus>
                @error('password')
                    <span style="color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary w-full mt-4">Réinitialiser le mot de passe</button>
        </form>
    </div>
</div>
@endsection
