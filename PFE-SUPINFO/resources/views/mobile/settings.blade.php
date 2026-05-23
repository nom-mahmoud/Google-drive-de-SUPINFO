@extends('layouts.mobile')
@section('title', 'Paramètres')
@section('header_title', 'Réglages')

@section('content')
@if(session('success'))
    <div style="background: var(--primary-light); color: var(--primary); padding: 0.75rem; border-radius: var(--radius-md); border: 1px solid var(--primary); margin-bottom: 1.5rem; font-size: 0.85rem; font-weight: 500;">
        {{ session('success') }}
    </div>
@endif

<!-- Update profile mobile card -->
<div class="bento-card" style="padding: 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.1rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Mon Profil
    </h3>
    
    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group" style="margin-bottom: 0.75rem;">
            <input type="text" name="firstname" value="{{ old('firstname', $user->firstname) }}" class="form-input" placeholder="Prénom" required style="width:100%;">
        </div>
        
        <div class="form-group" style="margin-bottom: 0.75rem;">
            <input type="text" name="lastname" value="{{ old('lastname', $user->lastname) }}" class="form-input" placeholder="Nom" required style="width:100%;">
        </div>

        <div class="form-group" style="margin-bottom: 0.75rem;">
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" placeholder="Email" required style="width:100%;">
        </div>

        <div class="form-group" style="margin-bottom: 0.75rem;">
            <input type="password" name="password" class="form-input" placeholder="Nouveau mot de passe" style="width:100%;">
        </div>

        <div class="form-group" style="margin-bottom: 1.25rem;">
            <input type="password" name="password_confirmation" class="form-input" placeholder="Confirmer mot de passe" style="width:100%;">
        </div>

        <button type="submit" class="btn btn-primary w-full">Enregistrer</button>
    </form>
</div>

<!-- Active Shares list mobile card -->
<div class="bento-card" style="padding: 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.1rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
        Mes Partages
    </h3>

    @if($shareLinks->isEmpty())
        <p style="text-align: center; color: var(--text-muted); font-size: 0.85rem; padding: 1.5rem 0;">Aucun lien actif.</p>
    @else
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @foreach($shareLinks as $link)
                @php
                    $isFolder = !is_null($link->folder_id);
                    $item = $isFolder ? $link->folder : $link->file;
                @endphp
                @if($item)
                <div style="background: var(--bg-color); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 0.75rem; display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.85rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="text-truncate" style="font-weight: 600; max-width: 70%;">{{ $item->name }}</span>
                        <form action="{{ route('shares.revoke', $link) }}" method="POST" onsubmit="return confirm('Révoquer ce lien ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:none; border:none; color:var(--danger); font-weight:600; font-size:0.8rem;">Révoquer</button>
                        </form>
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                        <input type="text" readonly value="{{ route('shares.public', $link->token) }}" style="flex:1; border: 1px solid var(--border-color); border-radius:4px; padding:0.2rem; font-size:0.75rem; background: var(--surface-color);">
                        <button onclick="navigator.clipboard.writeText('{{ route('shares.public', $link->token) }}'); alert('Copié !')" class="btn btn-outline" style="padding:0.2rem 0.5rem; font-size:0.75rem;">Copier</button>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    @endif
</div>

<!-- Logout Mobile Button -->
<form action="{{ route('logout') }}" method="POST" style="margin-bottom: 2rem;">
    @csrf
    <button type="submit" class="btn btn-outline w-full" style="color: var(--danger); border-color: var(--danger);">Déconnexion</button>
</form>
@endsection
