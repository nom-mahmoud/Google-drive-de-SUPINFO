@extends('layouts.mobile')
@section('title', 'Accès Sécurisé')
@section('header_title', 'Accès Protégé')

@section('content')
<div style="padding: 1.5rem 0.5rem;">
    <div class="bento-card" style="padding: 1.5rem; border-radius: var(--radius-md);">
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" style="margin-bottom: 0.5rem;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <h3 style="font-size: 1.15rem;">Mot de passe requis</h3>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Ce partage est protégé.</p>
        </div>

        <form action="{{ route('shares.verify', $token) }}" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom: 1rem;">
                <input type="password" name="password" class="form-input" placeholder="Mot de passe" required style="width:100%;">
                @error('password')
                    <span style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-full">Déverrouiller</button>
        </form>
    </div>
</div>
@endsection
