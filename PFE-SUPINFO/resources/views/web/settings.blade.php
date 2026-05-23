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
    
    <!-- Column 1: Mon Profil & Plans de Stockage -->
    <div style="display: flex; flex-direction: column; gap: 2rem; align-self: start;">
        
        <!-- Profil Update Form -->
        <div class="bento-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="margin: 0; display: flex; align-items: center; gap: 0.5rem; font-size: 1.25rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Mon Profil
                </h3>
                <button type="button" class="btn btn-outline" style="font-size: 0.8rem; padding: 0.35rem 0.75rem;" onclick="openPasswordModal()">Modifier le mot de passe</button>
            </div>
            
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

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required>
                </div>

                <button type="submit" class="btn btn-primary w-full">Enregistrer les modifications</button>
            </form>
        </div>

        <!-- Plans & Stockage Card -->
        <div class="bento-card">
            <h3 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; font-size: 1.25rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg>
                Plans & Stockage Cloud
            </h3>
            
            <!-- Plan Actuel -->
            <div style="background: var(--primary-light); border: 1px solid var(--primary); border-radius: var(--radius-md); padding: 1rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-weight: 700; color: var(--primary); font-size: 0.95rem;">Plan Actuel : Standard (30 Go)</div>
                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">Inclus gratuitement avec votre inscription.</div>
                </div>
                <span style="background: var(--primary); color: #fff; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Actif</span>
            </div>
            
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem;">Achetez plus d'espace de stockage au-delà de 30 Go pour rentabiliser la plateforme :</p>
            
            <!-- Liste des autres plans -->
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                
                <!-- Plan Pro -->
                <div style="border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1rem; display: flex; align-items: center; justify-content: space-between; background: var(--bg-color);">
                    <div>
                        <div style="font-weight: 600; font-size: 0.9rem; color: var(--text-main);">Plan Pro (100 Go)</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">4.99 € / mois • Partage étendu</div>
                    </div>
                    <button type="button" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;" onclick="openPaymentModal('Plan Pro (100 Go)', '4.99')">Prendre Pro</button>
                </div>
                
                <!-- Plan Premium -->
                <div style="border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1rem; display: flex; align-items: center; justify-content: space-between; background: var(--bg-color);">
                    <div>
                        <div style="font-weight: 600; font-size: 0.9rem; color: var(--text-main);">Plan Premium (500 Go)</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">9.99 € / mois • Support prioritaire</div>
                    </div>
                    <button type="button" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;" onclick="openPaymentModal('Plan Premium (500 Go)', '9.99')">Prendre Premium</button>
                </div>

                <!-- Plan Business -->
                <div style="border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1rem; display: flex; align-items: center; justify-content: space-between; background: var(--bg-color);">
                    <div>
                        <div style="font-weight: 600; font-size: 0.9rem; color: var(--text-main);">Plan Business (2 To)</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">19.99 € / mois • Multi-utilisateurs</div>
                    </div>
                    <button type="button" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;" onclick="openPaymentModal('Plan Business (2 To)', '19.99')">Prendre Business</button>
                </div>
            </div>
        </div>

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

<!-- Password Change Modal -->
<div class="modal-overlay" id="password-modal" style="display:none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px); align-items: center; justify-content: center; z-index: 9999;">
    <div class="modal-card bento-card" style="width: 100%; max-width: 400px; padding: 1.5rem; background: var(--bg-card); border-radius: 1rem;">
        <h3 style="margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            Modifier le mot de passe
        </h3>
        <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1.5rem;">Saisissez votre nouveau mot de passe ci-dessous.</p>
        
        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="firstname" value="{{ $user->firstname }}">
            <input type="hidden" name="lastname" value="{{ $user->lastname }}">
            <input type="hidden" name="email" value="{{ $user->email }}">
            
            <div class="form-group" style="margin-bottom: 1rem;">
                <label class="form-label">Nouveau mot de passe</label>
                <input type="password" name="password" class="form-input" required placeholder="Nouveau mot de passe" style="width:100%;">
            </div>
            
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" class="form-input" required placeholder="Confirmer le mot de passe" style="width:100%;">
            </div>
            
            <div class="flex gap-4" style="justify-content:flex-end; display: flex; gap: 1rem;">
                <button type="button" class="btn btn-outline" onclick="closePasswordModal()">Annuler</button>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>

<!-- Stripe Payment Modal -->
<div class="modal-overlay" id="payment-modal" style="display:none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px); align-items: center; justify-content: center; z-index: 9999;">
    <div class="modal-card bento-card" style="width: 100%; max-width: 450px; padding: 2rem; background: var(--bg-card); border-radius: 1rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                Paiement Stripe Cloud
            </h3>
            <button type="button" class="btn btn-outline" style="padding: 0.2rem 0.5rem; min-width: auto; height: auto;" onclick="closePaymentModal()">✕</button>
        </div>
        
        <div style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
            <div style="font-size: 0.8rem; color: var(--text-muted);">Abonnement sélectionné :</div>
            <div id="payment-plan-name" style="font-weight: 700; font-size: 1.1rem; color: var(--primary); margin-top: 0.25rem;">Plan Pro (100 Go)</div>
            <div style="font-size: 1.25rem; font-weight: 800; margin-top: 0.5rem;" id="payment-plan-price">4.99 € <span style="font-size: 0.85rem; font-weight: normal; color: var(--text-muted);">/ mois</span></div>
        </div>

        <form id="payment-modal-form" onsubmit="handleMockPayment(event)">
            <div class="form-group" style="margin-bottom: 1rem;">
                <label class="form-label">Numéro de carte (Stripe Test)</label>
                <input type="text" class="form-input" required value="4242 4242 4242 4242" placeholder="4242 4242 4242 4242" style="width: 100%;">
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Expiration</label>
                    <input type="text" class="form-input" required value="12/28" placeholder="MM/AA" style="width: 100%;">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">CVC</label>
                    <input type="text" class="form-input" required value="424" placeholder="123" style="width: 100%;">
                </div>
            </div>
            
            <button type="submit" id="payment-submit-btn" class="btn btn-primary w-full" style="padding: 0.75rem;">Confirmer et Payer</button>
        </form>
    </div>
</div>

<script>
function openPasswordModal() {
    document.getElementById('password-modal').style.display = 'flex';
}

function closePasswordModal() {
    document.getElementById('password-modal').style.display = 'none';
}

function openPaymentModal(planName, price) {
    document.getElementById('payment-plan-name').textContent = planName;
    document.getElementById('payment-plan-price').innerHTML = price + ' € <span style="font-size: 0.85rem; font-weight: normal; color: var(--text-muted);">/ mois</span>';
    document.getElementById('payment-modal').style.display = 'flex';
}

function closePaymentModal() {
    document.getElementById('payment-modal').style.display = 'none';
}

function handleMockPayment(e) {
    e.preventDefault();
    const btn = document.getElementById('payment-submit-btn');
    btn.disabled = true;
    btn.textContent = "Traitement Stripe en cours...";
    setTimeout(() => {
        alert("💳 Paiement de test Stripe réussi ! Votre espace disque a été augmenté.");
        closePaymentModal();
        btn.disabled = false;
        btn.textContent = "Confirmer et Payer";
    }, 1500);
}
</script>
@endsection
