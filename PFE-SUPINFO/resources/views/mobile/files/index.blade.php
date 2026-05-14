@extends('layouts.mobile')
@section('title', 'Mes Fichiers')
@section('header_title', 'Explorateur')

@section('header_actions')
<button class="action-btn" title="Nouvelle action" style="width: 36px; height: 36px; background: var(--bg-color);">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
</button>
@endsection

@section('content')
<!-- Search -->
<div class="search-box" style="margin-bottom: 1.5rem;">
    <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; margin-top: 10px; margin-left: 10px; color: var(--text-muted);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" id="file-search" class="form-input" placeholder="Rechercher..." style="padding-left: 2.5rem; width: 100%;">
</div>

<!-- Upload Progress (Hidden by default) -->
<div class="upload-progress-container" id="upload-progress-container" style="margin-bottom: 1.5rem; display: none;">
    <div style="font-size: 0.85rem; margin-bottom: 0.5rem; color: var(--text-main);" id="upload-text">Envoi en cours...</div>
    <div class="upload-progress-bar"><div class="upload-progress-fill" id="upload-progress-fill"></div></div>
</div>

<!-- Mobile File List -->
<div class="mobile-list" id="file-grid">
    <!-- Dossiers -->
    <div class="mobile-list-item" data-type="folder">
        <svg class="file-icon folder-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M10 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/></svg>
        <div class="file-info">
            <div class="file-name">Projets PFE</div>
            <div class="file-meta">12 éléments • Modifié il y a 2h</div>
        </div>
        <button class="action-btn" style="background: none; box-shadow: none;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg></button>
    </div>
    <div class="mobile-list-item" data-type="folder">
        <svg class="file-icon folder-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M10 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/></svg>
        <div class="file-info">
            <div class="file-name">Cours SUPINFO</div>
            <div class="file-meta">8 éléments • Modifié hier</div>
        </div>
        <button class="action-btn" style="background: none; box-shadow: none;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg></button>
    </div>

    <!-- Fichiers -->
    <div class="mobile-list-item" data-type="pdf">
        <svg class="file-icon" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        <div class="file-info">
            <div class="file-name">Rapport_PFE.pdf</div>
            <div class="file-meta">2.4 Mo • 14 mai 2026</div>
        </div>
        <button class="action-btn" style="background: none; box-shadow: none;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg></button>
    </div>
    <div class="mobile-list-item" data-type="image">
        <svg class="file-icon" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
        <div class="file-info">
            <div class="file-name">architecture.png</div>
            <div class="file-meta">890 Ko • 12 mai 2026</div>
        </div>
        <button class="action-btn" style="background: none; box-shadow: none;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg></button>
    </div>
</div>

<!-- Trash Banner Mobile -->
<div class="trash-banner" style="margin-top: 1.5rem; display: flex; align-items: center; justify-content: space-between; padding: 1rem; background: var(--bg-color); border-radius: var(--radius-md); border: 1px dashed var(--border-color);">
    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: var(--text-muted);">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
        3 éléments
    </div>
    <a href="#" style="font-size: 0.85rem; font-weight: 500;">Voir corbeille</a>
</div>

<!-- Floating Action Button (Upload) -->
<input type="file" id="mobile-file-input" style="display:none;" multiple>
<div class="fab" id="fab-upload">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
</div>

@endsection
