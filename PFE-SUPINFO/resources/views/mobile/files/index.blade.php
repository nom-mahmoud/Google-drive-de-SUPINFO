@extends('layouts.mobile')
@section('title', 'Mes Fichiers')
@section('header_title', 'Explorateur')

@section('header_actions')
<button class="action-btn" title="Nouvelle action" style="width: 36px; height: 36px; background: var(--bg-color);" onclick="document.getElementById('folder-modal').style.display='flex'">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/><line x1="12" y1="11" x2="12" y2="17"/><line x1="9" y1="14" x2="15" y2="14"/></svg>
</button>
@endsection

@section('content')
@if(session('success'))
    <div style="background: var(--primary-light); color: var(--success); padding: 0.75rem; border-radius: var(--radius-md); border: 1px solid var(--success); margin-bottom: 1.5rem; font-size: 0.85rem; font-weight: 500;">
        ✓ {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="error-alert-container" style="background: rgba(239, 68, 68, 0.1); color: var(--danger); padding: 0.75rem; border-radius: var(--radius-md); border: 1px solid var(--danger); margin-bottom: 1.5rem; font-size: 0.85rem;">
        <div style="font-weight: 600; margin-bottom: 0.25rem;">⚠️ Une erreur est survenue :</div>
        <ul style="margin: 0; padding-left: 1.25rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Search -->
<div class="search-box" style="margin-bottom: 1.5rem;">
    <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; margin-top: 10px; margin-left: 10px; color: var(--text-muted);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" id="file-search" class="form-input" placeholder="Rechercher..." style="padding-left: 2.5rem; width: 100%;">
</div>

<!-- Mobile Upload Progress Bar -->
<div class="upload-progress-container bento-card" id="upload-progress-container" style="display: none; margin-bottom: 1.5rem; padding: 1.25rem; border-radius: var(--radius-md);">
    <div style="font-size: 0.85rem; font-weight: 600; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-main);" id="upload-text">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        Envoi en cours...
    </div>
    <div class="upload-progress-bar" style="height: 6px; background-color: var(--border-color); border-radius: 3px; overflow: hidden;">
        <div class="upload-progress-fill" id="upload-progress-fill" style="height: 100%; width: 0%; background: linear-gradient(90deg, var(--primary), #60A5FA); transition: width 0.3s ease;"></div>
    </div>
</div>

<!-- Breadcrumb -->
@if($currentFolder)
<div style="margin-bottom: 1rem; font-size: 0.9rem;">
    <a href="{{ route('files.index') }}">Accueil</a>
    @foreach($breadcrumbs as $breadcrumb)
        / <a href="{{ route('files.index', ['folder_id' => $breadcrumb->id]) }}">{{ $breadcrumb->name }}</a>
    @endforeach
</div>
@endif

<!-- Mobile File List -->
<div class="mobile-list" id="file-grid">
    @if($folders->isEmpty() && $files->isEmpty())
        <p style="text-align: center; color: var(--text-muted); padding: 2rem;">Ce dossier est vide.</p>
    @endif

    <!-- Dossiers -->
    @foreach($folders as $folder)
    <div class="mobile-list-item" data-type="folder" onclick="window.location='{{ route('files.index', ['folder_id' => $folder->id]) }}'">
        <svg class="file-icon folder-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M10 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/></svg>
        <div class="file-info" style="flex: 1; overflow: hidden; min-width: 0;">
            <div class="file-name" style="font-weight: 500; font-size: 0.95rem; color: var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 0.1rem;">{{ $folder->name }}</div>
            <div class="file-meta" style="font-size: 0.75rem; color: var(--text-muted);">{{ $folder->updated_at->diffForHumans() }}</div>
        </div>
        <button type="button" class="action-btn" style="background: none; box-shadow: none; padding: 0.5rem; color: var(--text-muted); display: flex; align-items: center; justify-content: center;" title="Actions" onclick="event.stopPropagation(); openFolderActionSheet('{{ addslashes($folder->name) }}', '{{ route('folders.rename', $folder) }}', '{{ route('folders.move', $folder) }}', '{{ $folder->parent_id }}', '{{ route('folders.destroy', $folder) }}')">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
        </button>
    </div>
    @endforeach

    <!-- Fichiers -->
    @foreach($files as $file)
    <div class="mobile-list-item" data-type="file" style="cursor: pointer;" onclick="openFileActionSheet('{{ addslashes($file->name) }}', '{{ route('files.preview', $file) }}', '{{ $file->mime_type }}', '{{ route('files.rename', $file) }}', '{{ route('files.move', $file) }}', '{{ $file->folder_id }}', '{{ route('files.download', $file) }}', '{{ $file->id }}', '{{ route('files.destroy', $file) }}')">
        @php
            $iconColor = '#6B7280';
            if (Str::contains($file->mime_type, 'image')) $iconColor = '#10B981';
            elseif (Str::contains($file->mime_type, 'pdf')) $iconColor = '#EF4444';
            elseif (Str::contains($file->mime_type, 'word')) $iconColor = '#2563EB';
        @endphp
        <svg class="file-icon" viewBox="0 0 24 24" fill="none" stroke="{{ $iconColor }}" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        <div class="file-info" style="flex: 1; overflow: hidden; min-width: 0;">
            <div class="file-name" style="font-weight: 500; font-size: 0.95rem; color: var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 0.1rem;">{{ $file->name }}</div>
            <div class="file-meta" style="font-size: 0.75rem; color: var(--text-muted);">{{ number_format($file->size / 1024, 1) }} Ko</div>
        </div>
        <button type="button" class="action-btn" style="background: none; box-shadow: none; padding: 0.5rem; color: var(--text-muted); display: flex; align-items: center; justify-content: center;" title="Actions" onclick="event.stopPropagation(); openFileActionSheet('{{ addslashes($file->name) }}', '{{ route('files.preview', $file) }}', '{{ $file->mime_type }}', '{{ route('files.rename', $file) }}', '{{ route('files.move', $file) }}', '{{ $file->folder_id }}', '{{ route('files.download', $file) }}', '{{ $file->id }}', '{{ route('files.destroy', $file) }}')">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
        </button>
    </div>
    @endforeach
</div>

<!-- Trash Banner Mobile -->
<div class="trash-banner" style="margin-top: 1.5rem; display: flex; align-items: center; justify-content: space-between; padding: 1rem; background: var(--bg-color); border-radius: var(--radius-md); border: 1px dashed var(--border-color);">
    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: var(--text-muted);">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
        Corbeille
    </div>
    <a href="{{ route('files.trash') }}" style="font-size: 0.85rem; font-weight: 500;">Ouvrir</a>
</div>

<!-- New Folder Modal -->
<div class="modal-overlay" id="folder-modal" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 1rem;">
    <div class="modal-card bento-card" style="width: 100%; background: var(--bg-card); padding: 1.5rem; border-radius: 1rem;">
        <h3 style="margin-bottom:1.5rem;">Nouveau dossier</h3>
        <form action="{{ route('folders.store') }}" method="POST">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $currentFolder ? $currentFolder->id : '' }}">
            <div class="form-group">
                <input type="text" name="name" class="form-input" placeholder="Nom du dossier" required style="width: 100%;">
            </div>
            <div class="flex gap-4" style="justify-content:flex-end; margin-top: 1rem; display: flex; gap: 1rem;">
                <button type="button" class="btn btn-outline" onclick="closeModal('folder-modal')">Annuler</button>
                <button type="submit" class="btn btn-primary">Créer</button>
            </div>
        </form>
    </div>
</div>

<!-- Floating Action Button (Upload) -->
<form id="mobile-upload-form" action="{{ route('files.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="folder_id" value="{{ $currentFolder ? $currentFolder->id : '' }}">
    <input type="file" id="mobile-file-input" name="files[]" style="display:none;" multiple>
</form>
<div class="fab" id="fab-upload" onclick="document.getElementById('mobile-file-input').click()">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
</div>

<!-- Share Link Modal -->
<div class="modal-overlay" id="share-modal" style="display:none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px); align-items: center; justify-content: center; z-index: 9999; padding: 1rem;">
    <div class="modal-card bento-card" style="width: 100%; max-width: 450px; background: var(--bg-card); padding: 1.5rem; border-radius: 1rem;">
        <h3 style="margin-bottom:0.5rem;" id="share-modal-title">Partager un fichier</h3>
        <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1.5rem;">Générez un lien public sécurisé pour partager ce fichier.</p>
        
        <form action="{{ route('shares.store') }}" method="POST">
            @csrf
            <input type="hidden" name="file_id" id="share-file-id">
            
            <div class="form-group" style="margin-bottom: 1rem;">
                <label class="form-label">Type de protection</label>
                <select id="password-requirement" class="form-input" onchange="togglePasswordRequirement(this)" style="width: 100%;">
                    <option value="optional">Mot de passe optionnel</option>
                    <option value="required">Mot de passe obligatoire</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label class="form-label" id="password-label">Mot de passe d'accès</label>
                <input type="password" name="password" id="share-password-input" class="form-input" placeholder="Laisser vide pour un accès libre" style="width: 100%;">
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label">Date d'expiration (Optionnel)</label>
                <input type="datetime-local" name="expires_at" class="form-input" style="width: 100%;">
            </div>

            <div class="flex gap-4" style="justify-content:flex-end; display: flex; gap: 1rem;">
                <button type="button" class="btn btn-outline" onclick="closeModal('share-modal')">Annuler</button>
                <button type="submit" class="btn btn-primary">Générer le lien</button>
            </div>
        </form>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal-overlay" id="preview-modal" style="display:none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(4px); align-items: center; justify-content: center; z-index: 9999; padding: 1rem;">
    <div class="modal-card bento-card" style="width: 100%; height: 80%; display: flex; flex-direction: column; background: var(--bg-card); padding: 1.5rem; border-radius: 1rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 id="preview-modal-title" style="margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 80%;">Aperçu du fichier</h3>
            <button onclick="closePreviewModal()" class="btn btn-outline" style="padding: 0.25rem 0.5rem; min-width: auto; height: auto;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div id="preview-modal-body" style="flex: 1; overflow: auto; display: flex; align-items: center; justify-content: center; background: #000; border-radius: 0.5rem;">
            <!-- Content injected dynamically -->
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="delete-modal" style="display:none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px); align-items: center; justify-content: center; z-index: 9999; padding: 1rem;">
    <div class="modal-card bento-card" style="width: 100%; max-width: 400px; background: var(--bg-card); padding: 1.5rem; border-radius: 1rem;">
        <h3 style="margin-bottom:0.5rem; color: var(--danger);">Confirmer la suppression</h3>
        <p id="delete-modal-message" style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">Voulez-vous vraiment déplacer cet élément dans la corbeille ?</p>
        <form id="delete-modal-form" action="" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex gap-4" style="justify-content:flex-end; display: flex; gap: 1rem;">
                <button type="button" class="btn btn-outline" onclick="closeModal('delete-modal')">Annuler</button>
                <button type="submit" class="btn btn-primary" style="background: var(--danger); border-color: var(--danger);">Supprimer</button>
            </div>
        </form>
    </div>
</div>

<!-- Rename Modal -->
<div class="modal-overlay" id="rename-modal" style="display:none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px); align-items: center; justify-content: center; z-index: 9999; padding: 1rem;">
    <div class="modal-card bento-card" style="width: 100%; max-width: 400px; background: var(--bg-card); padding: 1.5rem; border-radius: 1rem;">
        <h3 style="margin-bottom:0.5rem;" id="rename-modal-title">Renommer</h3>
        <p style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 1.25rem;">Saisissez le nouveau nom.</p>
        <form id="rename-modal-form" action="" method="POST">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <input type="text" name="name" id="rename-name-input" class="form-input" placeholder="Nouveau nom" required autofocus style="width: 100%;">
            </div>
            <div class="flex gap-4" style="justify-content:flex-end; display: flex; gap: 1rem; margin-top: 1.25rem;">
                <button type="button" class="btn btn-outline" onclick="closeModal('rename-modal')">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Move Modal -->
<div class="modal-overlay" id="move-modal" style="display:none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px); align-items: center; justify-content: center; z-index: 9999; padding: 1rem;">
    <div class="modal-card bento-card" style="width: 100%; max-width: 400px; background: var(--bg-card); padding: 1.5rem; border-radius: 1rem;">
        <h3 style="margin-bottom:0.5rem;" id="move-modal-title">Déplacer</h3>
        <p style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 1.25rem;">Sélectionnez le dossier de destination.</p>
        <form id="move-modal-form" action="" method="POST">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <select name="folder_id" id="move-folder-select" class="form-input" style="width: 100%;">
                    <option value="">Accueil (Racine)</option>
                    @foreach($allFolders as $f)
                        <option value="{{ $f->id }}">{{ $f->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-4" style="justify-content:flex-end; display: flex; gap: 1rem; margin-top: 1.25rem;">
                <button type="button" class="btn btn-outline" onclick="closeModal('move-modal')">Annuler</button>
                <button type="submit" class="btn btn-primary">Déplacer</button>
            </div>
        </form>
    </div>
</div>

<!-- Bottom Sheet Actions Mobile -->
<div class="modal-overlay" id="action-sheet" style="display:none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(2px); align-items: flex-end; justify-content: center; z-index: 9999;">
    <div class="modal-card" style="width: 100%; max-width: 500px; background: var(--bg-card); border-radius: 1.5rem 1.5rem 0 0; padding: 1.5rem; box-shadow: 0 -8px 24px rgba(0,0,0,0.15); transform: translateY(100%); transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1); box-sizing: border-box;">
        <div style="width: 40px; height: 4px; background: var(--border-color); border-radius: 2px; margin: 0 auto 1.25rem auto;"></div>
        <h3 id="action-sheet-title" style="margin: 0 0 1.25rem 0; font-size: 1.1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 90%;">Options</h3>
        
        <div style="display: flex; flex-direction: column; gap: 0.25rem;">
            <button id="action-sheet-preview" class="action-sheet-item" style="display: flex; align-items: center; gap: 1rem; width: 100%; padding: 0.85rem 1rem; background: none; border: none; font-size: 0.95rem; text-align: left; color: var(--text-main); border-radius: var(--radius-md); cursor: pointer;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                Aperçu
            </button>
            <a id="action-sheet-download" class="action-sheet-item" style="display: flex; align-items: center; gap: 1rem; width: 100%; padding: 0.85rem 1rem; background: none; border: none; font-size: 0.95rem; text-align: left; color: var(--text-main); border-radius: var(--radius-md); cursor: pointer; text-decoration: none; box-sizing: border-box;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Télécharger
            </a>
            <button id="action-sheet-share" class="action-sheet-item" style="display: flex; align-items: center; gap: 1rem; width: 100%; padding: 0.85rem 1rem; background: none; border: none; font-size: 0.95rem; text-align: left; color: var(--text-main); border-radius: var(--radius-md); cursor: pointer;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                Partager
            </button>
            <button id="action-sheet-rename" class="action-sheet-item" style="display: flex; align-items: center; gap: 1rem; width: 100%; padding: 0.85rem 1rem; background: none; border: none; font-size: 0.95rem; text-align: left; color: var(--text-main); border-radius: var(--radius-md); cursor: pointer;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Renommer
            </button>
            <button id="action-sheet-move" class="action-sheet-item" style="display: flex; align-items: center; gap: 1rem; width: 100%; padding: 0.85rem 1rem; background: none; border: none; font-size: 0.95rem; text-align: left; color: var(--text-main); border-radius: var(--radius-md); cursor: pointer;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                Déplacer
            </button>
            <button id="action-sheet-delete" class="action-sheet-item" style="display: flex; align-items: center; gap: 1rem; width: 100%; padding: 0.85rem 1rem; background: none; border: none; font-size: 0.95rem; text-align: left; color: var(--danger); border-radius: var(--radius-md); cursor: pointer;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-danger"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg>
                Supprimer
            </button>
        </div>
        
        <button onclick="closeActionSheet()" class="btn btn-outline w-full" style="margin-top: 1.25rem; padding: 0.75rem; border-radius: var(--radius-md);">Fermer</button>
    </div>
</div>

<style>
.action-sheet-item {
    transition: background-color 0.2s;
}
.action-sheet-item:active {
    background-color: rgba(0, 0, 0, 0.05) !important;
}
.dark-theme .action-sheet-item:active {
    background-color: rgba(255, 255, 255, 0.08) !important;
}
</style>

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

function openFileActionSheet(name, previewUrl, mime, renameUrl, moveUrl, currentParent, downloadUrl, id, deleteUrl) {
    document.getElementById('action-sheet-title').textContent = name;
    
    // Actions elements
    const previewBtn = document.getElementById('action-sheet-preview');
    const downloadLnk = document.getElementById('action-sheet-download');
    const shareBtn = document.getElementById('action-sheet-share');
    const renameBtn = document.getElementById('action-sheet-rename');
    const moveBtn = document.getElementById('action-sheet-move');
    const deleteBtn = document.getElementById('action-sheet-delete');
    
    // Setup file actions
    previewBtn.style.display = 'flex';
    previewBtn.onclick = function() {
        closeActionSheet();
        openPreviewModal(previewUrl, mime, name);
    };
    
    downloadLnk.style.display = 'flex';
    downloadLnk.href = downloadUrl;
    downloadLnk.onclick = function() {
        closeActionSheet();
    };
    
    shareBtn.style.display = 'flex';
    shareBtn.onclick = function() {
        closeActionSheet();
        openShareModal(id, name);
    };
    
    renameBtn.onclick = function() {
        closeActionSheet();
        const form = document.getElementById('rename-modal-form');
        const input = document.getElementById('rename-name-input');
        const title = document.getElementById('rename-modal-title');
        
        form.action = renameUrl;
        input.value = name;
        title.textContent = `Renommer le fichier`;
        
        openModal('rename-modal');
    };
    
    moveBtn.onclick = function() {
        closeActionSheet();
        const form = document.getElementById('move-modal-form');
        const select = document.getElementById('move-folder-select');
        const title = document.getElementById('move-modal-title');
        
        form.action = moveUrl;
        select.name = 'folder_id';
        select.value = currentParent || '';
        title.textContent = `Déplacer le fichier`;
        
        openModal('move-modal');
    };
    
    deleteBtn.onclick = function() {
        closeActionSheet();
        openDeleteModal(deleteUrl, name, false);
    };
    
    // Open action sheet overlay
    const overlay = document.getElementById('action-sheet');
    overlay.style.display = 'flex';
    setTimeout(() => {
        overlay.classList.add('active');
        overlay.querySelector('.modal-card').style.transform = 'translateY(0)';
    }, 10);
}

function openFolderActionSheet(name, renameUrl, moveUrl, currentParent, deleteUrl) {
    document.getElementById('action-sheet-title').textContent = name;
    
    // Actions elements
    const previewBtn = document.getElementById('action-sheet-preview');
    const downloadLnk = document.getElementById('action-sheet-download');
    const shareBtn = document.getElementById('action-sheet-share');
    const renameBtn = document.getElementById('action-sheet-rename');
    const moveBtn = document.getElementById('action-sheet-move');
    const deleteBtn = document.getElementById('action-sheet-delete');
    
    // Hide file-only actions
    previewBtn.style.display = 'none';
    downloadLnk.style.display = 'none';
    shareBtn.style.display = 'none';
    
    // Setup actions
    renameBtn.onclick = function() {
        closeActionSheet();
        const form = document.getElementById('rename-modal-form');
        const input = document.getElementById('rename-name-input');
        const title = document.getElementById('rename-modal-title');
        
        form.action = renameUrl;
        input.value = name;
        title.textContent = `Renommer le dossier`;
        
        openModal('rename-modal');
    };
    
    moveBtn.onclick = function() {
        closeActionSheet();
        const form = document.getElementById('move-modal-form');
        const select = document.getElementById('move-folder-select');
        const title = document.getElementById('move-modal-title');
        
        form.action = moveUrl;
        select.name = 'parent_id';
        select.value = currentParent || '';
        title.textContent = `Déplacer le dossier`;
        
        openModal('move-modal');
    };
    
    deleteBtn.onclick = function() {
        closeActionSheet();
        openDeleteModal(deleteUrl, name, true);
    };
    
    // Open action sheet overlay
    const overlay = document.getElementById('action-sheet');
    overlay.style.display = 'flex';
    setTimeout(() => {
        overlay.classList.add('active');
        overlay.querySelector('.modal-card').style.transform = 'translateY(0)';
    }, 10);
}

function closeActionSheet() {
    const overlay = document.getElementById('action-sheet');
    if (overlay) {
        overlay.querySelector('.modal-card').style.transform = 'translateY(100%)';
        overlay.classList.remove('active');
        setTimeout(() => {
            overlay.style.display = 'none';
        }, 300);
    }
}

function triggerPreview(btn) {
    openPreviewModal(btn.getAttribute('data-url'), btn.getAttribute('data-mime'), btn.getAttribute('data-name'));
}

function triggerShare(btn) {
    openShareModal(btn.getAttribute('data-id'), btn.getAttribute('data-name'));
}

function triggerDelete(btn) {
    const isFolder = btn.getAttribute('data-folder') === 'true';
    openDeleteModal(btn.getAttribute('data-url'), btn.getAttribute('data-name'), isFolder);
}

function triggerRename(btn, isFolder) {
    const form = document.getElementById('rename-modal-form');
    const input = document.getElementById('rename-name-input');
    const title = document.getElementById('rename-modal-title');
    
    form.action = btn.getAttribute('data-url');
    input.value = btn.getAttribute('data-name');
    title.textContent = `Renommer le ${isFolder ? 'dossier' : 'fichier'}`;
    
    openModal('rename-modal');
}

function triggerMove(btn, isFolder) {
    const form = document.getElementById('move-modal-form');
    const select = document.getElementById('move-folder-select');
    const title = document.getElementById('move-modal-title');
    
    form.action = btn.getAttribute('data-url');
    select.name = isFolder ? 'parent_id' : 'folder_id';
    
    const currentParent = btn.getAttribute('data-current-parent') || '';
    select.value = currentParent;
    
    title.textContent = `Déplacer le ${isFolder ? 'dossier' : 'fichier'}`;
    
    openModal('move-modal');
}

function togglePasswordRequirement(select) {
    const pwdInput = document.getElementById('share-password-input');
    const pwdLabel = document.getElementById('password-label');
    if (select.value === 'required') {
        pwdInput.required = true;
        pwdInput.placeholder = "Saisissez le mot de passe requis";
        pwdLabel.textContent = "Mot de passe d'accès (Obligatoire)";
    } else {
        pwdInput.required = false;
        pwdInput.placeholder = "Laisser vide pour un accès libre";
        pwdLabel.textContent = "Mot de passe d'accès (Optionnel)";
    }
}

function openShareModal(fileId, itemName) {
    document.getElementById('share-file-id').value = fileId || '';
    document.getElementById('share-modal-title').textContent = 'Partager "' + itemName + '"';
    
    // Reset selection state
    const select = document.getElementById('password-requirement');
    select.value = 'optional';
    togglePasswordRequirement(select);
    document.getElementById('share-password-input').value = '';
    
    openModal('share-modal');
}

function openPreviewModal(url, mimeType, name) {
    const title = document.getElementById('preview-modal-title');
    const body = document.getElementById('preview-modal-body');
    title.textContent = name;
    body.innerHTML = '';

    if (mimeType.includes('image')) {
        const img = document.createElement('img');
        img.src = url;
        img.style.maxWidth = '100%';
        img.style.maxHeight = '100%';
        img.style.objectFit = 'contain';
        body.appendChild(img);
    } else if (mimeType.includes('pdf')) {
        const iframe = document.createElement('iframe');
        iframe.src = url;
        iframe.style.width = '100%';
        iframe.style.height = '100%';
        iframe.style.border = 'none';
        body.appendChild(iframe);
    } else {
        const iframe = document.createElement('iframe');
        iframe.src = url;
        iframe.style.width = '100%';
        iframe.style.height = '100%';
        iframe.style.border = 'none';
        iframe.style.background = '#fff';
        body.appendChild(iframe);
    }
    
    openModal('preview-modal');
}

function closePreviewModal() {
    closeModal('preview-modal');
    setTimeout(() => {
        document.getElementById('preview-modal-body').innerHTML = '';
    }, 300);
}

function openDeleteModal(actionUrl, itemName, isFolder) {
    const form = document.getElementById('delete-modal-form');
    const msg = document.getElementById('delete-modal-message');
    form.action = actionUrl;
    msg.textContent = `Voulez-vous vraiment déplacer le ${isFolder ? 'dossier' : 'fichier'} "${itemName}" dans la corbeille ?`;
    openModal('delete-modal');
}
</script>
@endsection
