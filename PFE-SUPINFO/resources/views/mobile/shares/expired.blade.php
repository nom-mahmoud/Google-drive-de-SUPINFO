@extends('layouts.mobile')
@section('title', 'Lien Expiré')
@section('header_title', 'Lien Expiré')

@section('content')
<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 2rem 1rem; margin-top: 2rem;">
    <div style="width: 64px; height: 64px; background: rgba(239, 68, 68, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; color: var(--danger);">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
    </div>
    <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.75rem;">Ce lien a expiré</h2>
    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 2rem; line-height: 1.5;">
        Désolé, ce lien de partage public a expiré ou a été révoqué par son propriétaire.
    </p>
    @if(isset($shareLink) && $shareLink->expires_at)
        <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: -1rem; margin-bottom: 2rem; background: rgba(239, 68, 68, 0.05); padding: 0.5rem; border-radius: var(--radius-sm); border: 1px dashed rgba(239, 68, 68, 0.2); width: 100%; box-sizing: border-box;">
            Ce lien a expiré le <strong>{{ $shareLink->expires_at->format('d/m/Y') }}</strong> à <strong>{{ $shareLink->expires_at->format('H:i') }}</strong>.
        </p>
    @endif
    <a href="/" class="btn btn-primary w-full" style="padding: 0.75rem;">
        Retour
    </a>
</div>
@endsection
