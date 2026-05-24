@extends('layouts.mobile')
@section('title', 'Mon Profil')
@section('header_title', 'Mon Profil')

@section('content')
@if(session('success'))
    <div style="background: var(--primary-light); color: var(--primary); padding: 0.75rem; border-radius: var(--radius-md); border: 1px solid var(--primary); margin-bottom: 1.5rem; font-size: 0.85rem; font-weight: 500;">
        {{ session('success') }}
    </div>
@endif

<!-- Update profile mobile card -->
<div class="bento-card" style="padding: 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h3 style="font-size: 1.1rem; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Profil
        </h3>
        <button type="button" class="btn btn-outline" style="font-size: 0.75rem; padding: 0.25rem 0.5rem; min-width: auto; height: auto;" onclick="openPasswordModal()">Modifier MDP</button>
    </div>
    
    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group" style="margin-bottom: 0.75rem;">
            <input type="text" name="firstname" value="{{ old('firstname', $user->firstname) }}" class="form-input" placeholder="Prénom" required style="width:100%;">
        </div>
        
        <div class="form-group" style="margin-bottom: 0.75rem;">
            <input type="text" name="lastname" value="{{ old('lastname', $user->lastname) }}" class="form-input" placeholder="Nom" required style="width:100%;">
        </div>

        <div class="form-group" style="margin-bottom: 1.25rem;">
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" placeholder="Email" required style="width:100%;">
        </div>

        <button type="submit" class="btn btn-primary w-full">Enregistrer</button>
    </form>
</div>

<!-- Plans & Stockage Cloud mobile card -->
<div class="bento-card" style="padding: 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.1rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/></svg>
        Plans & Stockage
    </h3>
    
    @php
        $planName = $user->plan ?? 'Standard';
        $storageFormatted = '30 Go';
        $planDesc = 'Gratuit et à vie';
        if ($planName === 'Pro') {
            $storageFormatted = '100 Go';
            $planDesc = 'Espace Pro actif';
        } elseif ($planName === 'Premium') {
            $storageFormatted = '500 Go';
            $planDesc = 'Espace Premium actif';
        } elseif ($planName === 'Business') {
            $storageFormatted = '2 To';
            $planDesc = 'Espace Business actif';
        }
    @endphp
    <!-- Plan Actuel -->
    <div style="background: var(--primary-light); border: 1px solid var(--primary); border-radius: var(--radius-md); padding: 0.75rem; margin-bottom: 1rem; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <div style="font-weight: 700; color: var(--primary); font-size: 0.85rem;">{{ $planName }} ({{ $storageFormatted }})</div>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.15rem;">{{ $planDesc }}</div>
        </div>
        <span style="background: var(--primary); color: #fff; padding: 0.15rem 0.35rem; border-radius: 4px; font-size: 0.65rem; font-weight: 600;">ACTIF</span>
    </div>
    
    <!-- Liste des plans mobile -->
    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
        <!-- Pro -->
        <div style="border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 0.75rem; display: flex; align-items: center; justify-content: space-between; background: var(--bg-color);">
            <div>
                <div style="font-weight: 600; font-size: 0.85rem; color: var(--text-main);">Pro (100 Go)</div>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.15rem;">4.99 € / mois</div>
            </div>
            @if($planName === 'Pro')
                <span style="background: var(--border-color); color: var(--text-muted); padding: 0.25rem 0.5rem; border-radius: var(--radius-sm); font-size: 0.75rem; font-weight: 600;">Actif</span>
            @else
                <form action="{{ route('payment.checkout') }}" method="POST" style="display:inline;">
                    @csrf
                    <input type="hidden" name="plan" value="Pro">
                    <button type="submit" class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; min-width: auto; height: auto;">Prendre</button>
                </form>
            @endif
        </div>
        
        <!-- Premium -->
        <div style="border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 0.75rem; display: flex; align-items: center; justify-content: space-between; background: var(--bg-color);">
            <div>
                <div style="font-weight: 600; font-size: 0.85rem; color: var(--text-main);">Premium (500 Go)</div>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.15rem;">9.99 € / mois</div>
            </div>
            @if($planName === 'Premium')
                <span style="background: var(--border-color); color: var(--text-muted); padding: 0.25rem 0.5rem; border-radius: var(--radius-sm); font-size: 0.75rem; font-weight: 600;">Actif</span>
            @else
                <form action="{{ route('payment.checkout') }}" method="POST" style="display:inline;">
                    @csrf
                    <input type="hidden" name="plan" value="Premium">
                    <button type="submit" class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; min-width: auto; height: auto;">Prendre</button>
                </form>
            @endif
        </div>

        <!-- Business -->
        <div style="border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 0.75rem; display: flex; align-items: center; justify-content: space-between; background: var(--bg-color);">
            <div>
                <div style="font-weight: 600; font-size: 0.85rem; color: var(--text-main);">Business (2 To)</div>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.15rem;">19.99 € / mois</div>
            </div>
            @if($planName === 'Business')
                <span style="background: var(--border-color); color: var(--text-muted); padding: 0.25rem 0.5rem; border-radius: var(--radius-sm); font-size: 0.75rem; font-weight: 600;">Actif</span>
            @else
                <form action="{{ route('payment.checkout') }}" method="POST" style="display:inline;">
                    @csrf
                    <input type="hidden" name="plan" value="Business">
                    <button type="submit" class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; min-width: auto; height: auto;">Prendre</button>
                </form>
            @endif
        </div>
    </div>
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
                        <button onclick="navigator.clipboard.writeText('{{ route('shares.public', $link->token) }}'); showToast('Lien copié ! 📋')" class="btn btn-outline" style="padding:0.2rem 0.5rem; font-size:0.75rem;">Copier</button>
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.25rem 0.75rem; font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem; align-items: center;">
                        <span>👁️ {{ $link->views_count }} vues</span>
                        <span>Créé : {{ $link->created_at->format('d M H:i') }}</span>
                        @if($link->expires_at)
                            <span style="color: var(--danger);">Expire : {{ $link->expires_at->format('d M H:i') }}</span>
                        @endif
                        @if($link->password)
                            <span style="color: var(--success); font-weight: 500;">🔒 Protégé</span>
                        @endif
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

<!-- Password Change Modal Overlay -->
<div class="modal-overlay" id="password-modal" style="display:none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px); align-items: center; justify-content: center; z-index: 9999; padding: 1rem;">
    <div class="modal-card bento-card" style="width: 100%; max-width: 400px; padding: 1.5rem; background: var(--bg-card); border-radius: 1rem;">
        <h3 style="margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem; font-size: 1.1rem;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            Nouveau mot de passe
        </h3>
        <p style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 1.25rem;">Saisissez votre nouveau mot de passe ci-dessous.</p>
        
        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="firstname" value="{{ $user->firstname }}">
            <input type="hidden" name="lastname" value="{{ $user->lastname }}">
            <input type="hidden" name="email" value="{{ $user->email }}">
            
            <div class="form-group" style="margin-bottom: 0.75rem;">
                <input type="password" name="password" class="form-input" required placeholder="Mot de passe" style="width:100%;">
            </div>
            
            <div class="form-group" style="margin-bottom: 1.25rem;">
                <input type="password" name="password_confirmation" class="form-input" required placeholder="Confirmer mot de passe" style="width:100%;">
            </div>
            
            <div class="flex gap-4" style="justify-content:flex-end; display: flex; gap: 1rem;">
                <button type="button" class="btn btn-outline" onclick="closePasswordModal()">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('active'), 10);
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove('active');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }
}

function openPasswordModal() {
    openModal('password-modal');
}

function closePasswordModal() {
    closeModal('password-modal');
}
</script>
@endsection

