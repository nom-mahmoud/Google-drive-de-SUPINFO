@extends('layouts.mobile')
@section('title', 'Mes Fichiers')
@section('header_title', 'Explorateur')

@section('header_actions')
<button class="action-btn" title="Nouvelle action" style="width: 36px; height: 36px; background: var(--bg-color);" onclick="document.getElementById('folder-modal').style.display='flex'">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/><line x1="12" y1="11" x2="12" y2="17"/><line x1="9" y1="14" x2="15" y2="14"/></svg>
</button>
@endsection

@section('content')
<!-- Search -->
<div class="search-box" style="margin-bottom: 1.5rem;">
    <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; margin-top: 10px; margin-left: 10px; color: var(--text-muted);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" id="file-search" class="form-input" placeholder="Rechercher..." style="padding-left: 2.5rem; width: 100%;">
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
        <div class="file-info">
            <div class="file-name">{{ $folder->name }}</div>
            <div class="file-meta">{{ $folder->updated_at->diffForHumans() }}</div>
        </div>
        <div style="display: flex; gap: 0.5rem;" onclick="event.stopPropagation()">
            <button type="button" class="action-btn" style="background: none; box-shadow: none; padding: 0;" title="Supprimer" data-url="{{ route('folders.destroy', $folder) }}" data-name="{{ $folder->name }}" data-folder="true" onclick="triggerDelete(this)"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-danger"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg></button>
        </div>
    </div>
    @endforeach

    <!-- Fichiers -->
    @foreach($files as $file)
    <div class="mobile-list-item" data-type="file">
        @php
            $iconColor = '#6B7280';
            if (Str::contains($file->mime_type, 'image')) $iconColor = '#10B981';
            elseif (Str::contains($file->mime_type, 'pdf')) $iconColor = '#EF4444';
        @endphp
        <svg class="file-icon" viewBox="0 0 24 24" fill="none" stroke="{{ $iconColor }}" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        <div class="file-info">
            <div class="file-name">{{ $file->name }}</div>
            <div class="file-meta">{{ number_format($file->size / 1024, 1) }} Ko</div>
        </div>
        <div style="display: flex; gap: 0.5rem;" onclick="event.stopPropagation()">
            <button type="button" class="action-btn" style="background: none; box-shadow: none; padding: 0;" title="Aperçu" data-url="{{ route('files.preview', $file) }}" data-mime="{{ $file->mime_type }}" data-name="{{ $file->name }}" onclick="triggerPreview(this)"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
            <a href="{{ route('files.download', $file) }}" class="action-btn" style="background: none; box-shadow: none; padding: 0;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></a>
            <button type="button" class="action-btn" style="background: none; box-shadow: none; padding: 0;" title="Partager" data-id="{{ $file->id }}" data-name="{{ $file->name }}" onclick="triggerShare(this)"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg></button>
            <button type="button" class="action-btn" style="background: none; box-shadow: none; padding: 0;" title="Supprimer" data-url="{{ route('files.destroy', $file) }}" data-name="{{ $file->name }}" data-folder="false" onclick="triggerDelete(this)"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-danger"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg></button>
        </div>
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
                <button type="button" class="btn btn-outline" onclick="document.getElementById('folder-modal').style.display='none'">Annuler</button>
                <button type="submit" class="btn btn-primary">Créer</button>
            </div>
        </form>
    </div>
</div>

<!-- Floating Action Button (Upload) -->
<form id="mobile-upload-form" action="{{ route('files.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="folder_id" value="{{ $currentFolder ? $currentFolder->id : '' }}">
    <input type="file" id="mobile-file-input" name="files[]" style="display:none;" multiple onchange="document.getElementById('mobile-upload-form').submit()">
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
                <button type="button" class="btn btn-outline" onclick="document.getElementById('share-modal').style.display='none'">Annuler</button>
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
                <button type="button" class="btn btn-outline" onclick="document.getElementById('delete-modal').style.display='none'">Annuler</button>
                <button type="submit" class="btn btn-primary" style="background: var(--danger); border-color: var(--danger);">Supprimer</button>
            </div>
        </form>
    </div>
</div>

<script>
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
    
    document.getElementById('share-modal').style.display = 'flex';
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
    
    document.getElementById('preview-modal').style.display = 'flex';
}

function closePreviewModal() {
    document.getElementById('preview-modal').style.display = 'none';
    document.getElementById('preview-modal-body').innerHTML = '';
}

function openDeleteModal(actionUrl, itemName, isFolder) {
    const form = document.getElementById('delete-modal-form');
    const msg = document.getElementById('delete-modal-message');
    form.action = actionUrl;
    msg.textContent = `Voulez-vous vraiment déplacer le ${isFolder ? 'dossier' : 'fichier'} "${itemName}" dans la corbeille ?`;
    document.getElementById('delete-modal').style.display = 'flex';
}
</script>
@endsection
