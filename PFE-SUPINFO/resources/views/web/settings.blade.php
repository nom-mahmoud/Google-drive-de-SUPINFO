@extends('layouts.web')
@section('title', 'Paramètres')

@section('content')
<div class="file-manager-header" style="margin-bottom: 2.5rem;">
    <div>
        <h1 class="page-title">Paramètres</h1>
        <p style="color: var(--text-muted);">Gérez vos informations personnelles et vos liens de partage publics.</p>
    </div>
</div>

@if(session('success'))
    <div style="background: var(--primary-light); color: var(--primary); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--primary); margin-bottom: 2rem; font-weight: 500;">
        {{ session('success') }}
    </div>
@endif

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
    
    <!-- Profil Update Form -->
    <div class="bento-card" style="align-self: start;">
        <h3 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; font-size: 1.25rem;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Mon Profil
        </h3>
        
        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label">Prénom</label>
                <input type="text" name="firstname" value="{{ old('firstname', $user->firstname) }}" class="form-input" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Nom</label>
                <input type="text" name="lastname" value="{{ old('lastname', $user->lastname) }}" class="form-input" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required>
            </div>

            <div class="form-group">
                <label class="form-label">Nouveau mot de passe (optionnel)</label>
                <input type="password" name="password" class="form-input" placeholder="Laisser vide pour ne pas changer">
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label class="form-label">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" class="form-input" placeholder="Laisser vide pour ne pas changer">
            </div>

            <button type="submit" class="btn btn-primary w-full">Enregistrer les modifications</button>
        </form>
    </div>

    <!-- Active Shares List -->
    <div class="bento-card">
        <h3 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; font-size: 1.25rem;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
            Mes Liens de Partage Publics
        </h3>

        @if($shareLinks->isEmpty())
            <div style="text-align: center; padding: 4rem 1rem; color: var(--text-muted);">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 1rem; opacity: 0.5;"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                <p>Vous n'avez créé aucun lien de partage public.</p>
            </div>
        @else
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach($shareLinks as $link)
                    @php
                        $isFolder = !is_null($link->folder_id);
                        $item = $isFolder ? $link->folder : $link->file;
                    @endphp
                    @if($item)
                    <div style="background: var(--bg-color); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.25rem; display: flex; flex-direction: column; gap: 0.75rem;">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; min-width: 0;">
                                @if($isFolder)
                                    <svg class="folder-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M10 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/></svg>
                                @else
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                @endif
                                <span class="text-truncate" style="font-weight: 600; font-size: 0.95rem;" title="{{ $item->name }}">{{ $item->name }}</span>
                            </div>
                            
                            <form action="{{ route('shares.revoke', $link) }}" method="POST" onsubmit="return confirm('Révoquer ce lien de partage ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline" style="color: var(--danger); border-color: rgba(239, 68, 68, 0.2); padding: 0.4rem 0.8rem; font-size: 0.85rem;">Révoquer</button>
                            </form>
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 0.5rem; background: var(--surface-color); border: 1px solid var(--border-color); padding: 0.5rem; border-radius: var(--radius-sm);">
                            <input type="text" readonly value="{{ route('shares.public', $link->token) }}" style="flex: 1; border: none; background: transparent; font-family: monospace; font-size: 0.8rem; color: var(--text-main); outline: none;">
                            <button onclick="navigator.clipboard.writeText('{{ route('shares.public', $link->token) }}'); alert('Lien copié !')" class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Copier</button>
                        </div>

                        <div style="display: flex; gap: 1rem; font-size: 0.75rem; color: var(--text-muted);">
                            <span>Créé le : {{ $link->created_at->format('d M Y H:i') }}</span>
                            @if($link->expires_at)
                                <span style="color: var(--danger);">Expire le : {{ $link->expires_at->format('d M Y H:i') }}</span>
                            @endif
                            @if($link->password)
                                <span style="color: var(--success); font-weight: 500;">🔒 Protégé par mot de passe</span>
                            @endif
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
