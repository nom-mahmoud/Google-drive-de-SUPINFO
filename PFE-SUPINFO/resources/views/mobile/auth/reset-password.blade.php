@extends('layouts.mobile')

@section('title', 'Réinitialiser le mot de passe')
@section('header_title', 'Nouveau de passe')

@section('content')
<div class="flex flex-col items-center mt-6 mb-6">
    <div class="auth-logo" style="font-size: 3rem; margin-bottom: 1rem;">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
    </div>
    <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">Nouveau mot de passe</h2>
    <p class="text-muted text-center" style="font-size: 0.875rem; margin-bottom: 2rem;">Saisissez votre nouveau mot de passe pour <strong>{{ $email }}</strong>.</p>
</div>

<form action="{{ route('password.update') }}" method="POST">
    @csrf
    <input type="hidden" name="oobCode" value="{{ $oobCode }}">

    <div class="form-group">
        <label for="password" class="form-label">Nouveau mot de passe</label>
        <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required autofocus style="padding: 1rem;">
        @error('password')
            <span style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="••••••••" required style="padding: 1rem;">
    </div>

    <button type="submit" class="btn btn-primary w-full mt-2" style="padding: 1rem; font-size: 1.125rem;">Réinitialiser</button>
</form>
@endsection
