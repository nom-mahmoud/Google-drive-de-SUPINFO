@extends('layouts.web')
@section('title', 'Lien Expiré')

@section('content')
<div class="container flex justify-center" style="margin-top: 4rem;">
    <div class="bento-card text-center" style="width: 100%; max-width: 500px; padding: 3rem 2rem;">
        <div style="width: 72px; height: 72px; background: rgba(239, 68, 68, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto; color: var(--danger);">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        </div>
        <h2 style="font-size: 1.75rem; margin-bottom: 0.75rem;">Ce lien a expiré</h2>
        <p style="color: var(--text-muted); font-size: 1rem; margin-bottom: 2rem; line-height: 1.6;">
            Désolé, ce lien de partage public a expiré ou a été révoqué par son propriétaire. Veuillez demander un nouveau lien d'accès.
        </p>
        @if(isset($shareLink) && $shareLink->expires_at)
            <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: -1rem; margin-bottom: 2rem; background: rgba(239, 68, 68, 0.05); padding: 0.5rem; border-radius: var(--radius-sm); border: 1px dashed rgba(239, 68, 68, 0.2); display: inline-block;">
                Ce lien a expiré le <strong>{{ $shareLink->expires_at->format('d/m/Y') }}</strong> à <strong>{{ $shareLink->expires_at->format('H:i') }}</strong>.
            </p>
        @endif
        <a href="/" class="btn btn-primary" style="padding: 0.75rem 2rem;">
            Retour à l'accueil
        </a>
    </div>
</div>
@endsection
