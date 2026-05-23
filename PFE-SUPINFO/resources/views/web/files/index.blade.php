@extends('layouts.web')
@section('title', 'Mes Fichiers')

@section('content')
@if(session('share_success'))
    <div class="bento-card" style="margin-bottom: 2rem; border-left: 6px solid var(--success); padding: 1.5rem;">
        <h4 style="color: var(--success); margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            {{ session('share_success') }}
        </h4>
        <div style="display: flex; gap: 1rem; align-items: center; margin-top: 1rem;">
            <input type="text" id="share-link-input" readonly value="{{ session('share_url') }}" class="form-input" style="flex: 1; font-family: monospace;">
            <button onclick="navigator.clipboard.writeText(document.getElementById('share-link-input').value); alert('Lien copié !')" class="btn btn-primary">Copier le lien</button>
        </div>
    </div>
@endif

<div class="file-manager-header">
    <div>
        <h1 class="page-title">Explorateur de fichiers</h1>
        <nav class="breadcrumb" id="breadcrumb">
            <a href="{{ route('files.index') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Accueil
            </a>
            @foreach($breadcrumbs as $breadcrumb)
                <span class="separator">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                </span>
                <a href="{{ route('files.index', ['folder_id' => $breadcrumb->id]) }}">{{ $breadcrumb->name }}</a>
            @endforeach
        </nav>
    </div>
    <div class="file-manager-actions">
        <div class="search-box">
            <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="file-search" class="form-input" placeholder="Rechercher un fichier..." style="padding-left: 2.5rem;">
        </div>
        <div class="view-toggle">
            <button class="view-btn active-view" id="view-grid" title="Vue grille">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
            </button>
            <button class="view-btn" id="view-list" title="Vue liste">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
            </button>
        </div>
        <button class="btn btn-outline" id="btn-new-folder" onclick="document.getElementById('folder-modal').style.display='flex'">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/><line x1="12" y1="11" x2="12" y2="17"/><line x1="9" y1="14" x2="15" y2="14"/></svg>
            Nouveau dossier
        </button>
        <form id="upload-form" action="{{ route('files.upload') }}" method="POST" enctype="multipart/form-data" style="display:inline;">
            @csrf
            <input type="hidden" name="folder_id" value="{{ $currentFolder ? $currentFolder->id : '' }}">
            <input type="file" id="file-input" name="files[]" style="display:none;" multiple onchange="document.getElementById('upload-form').submit()">
            <button type="button" class="btn btn-primary" onclick="document.getElementById('file-input').click()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                Envoyer un fichier
            </button>
        </form>
    </div>
</div>

<!-- File Grid -->
<div class="file-grid" id="file-grid">
    @if($folders->isEmpty() && $files->isEmpty())
        <div style="grid-column: 1 / -1; text-align: center; padding: 4rem; color: var(--text-muted);">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="margin-bottom: 1rem; opacity: 0.5;"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
            <p>Ce dossier est vide.</p>
        </div>
    @endif

    <!-- Dossiers -->
    @foreach($folders as $folder)
    <div class="file-card" data-type="folder" onclick="window.location='{{ route('files.index', ['folder_id' => $folder->id]) }}'">
        <div class="file-actions" onclick="event.stopPropagation()">
            <a href="{{ route('folders.download.zip', $folder) }}" class="action-btn" title="Télécharger ZIP"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></a>
            <button type="button" class="action-btn" title="Partager" onclick="openShareModal(null, {{ $folder->id }}, '{{ addslashes($folder->name) }}')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg></button>
            <form action="{{ route('folders.destroy', $folder) }}" method="POST" onsubmit="return confirm('Déplacer ce dossier dans la corbeille ?')" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn danger" title="Supprimer"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
            </form>
        </div>
        <svg class="file-icon folder-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M10 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/></svg>
        <div class="file-name">{{ $folder->name }}</div>
        <div class="file-meta">{{ $folder->children_count ?? 0 }} éléments • {{ $folder->updated_at->diffForHumans() }}</div>
    </div>
    @endforeach

    <!-- Fichiers -->
    @foreach($files as $file)
    <div class="file-card" data-type="file">
        <div class="file-actions" onclick="event.stopPropagation()">
            <a href="{{ route('files.preview', $file) }}" target="_blank" class="action-btn" title="Aperçu"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></a>
            <a href="{{ route('files.download', $file) }}" class="action-btn" title="Télécharger"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></a>
            <button type="button" class="action-btn" title="Partager" onclick="openShareModal({{ $file->id }}, null, '{{ addslashes($file->name) }}')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg></button>
            <form action="{{ route('files.destroy', $file) }}" method="POST" onsubmit="return confirm('Déplacer ce fichier dans la corbeille ?')" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn danger" title="Supprimer"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
            </form>
        </div>
        @php
            $iconColor = '#6B7280';
            if (Str::contains($file->mime_type, 'image')) $iconColor = '#10B981';
            elseif (Str::contains($file->mime_type, 'pdf')) $iconColor = '#EF4444';
            elseif (Str::contains($file->mime_type, 'word')) $iconColor = '#2563EB';
        @endphp
        <svg class="file-icon" viewBox="0 0 24 24" fill="none" stroke="{{ $iconColor }}" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        <div class="file-name text-truncate" title="{{ $file->name }}">{{ $file->name }}</div>
        <div class="file-meta">{{ number_format($file->size / 1024, 1) }} Ko • {{ $file->created_at->format('d M Y') }}</div>
    </div>
    @endforeach
</div>

<!-- New Folder Modal -->
<div class="modal-overlay" id="folder-modal" style="display:none;">
    <div class="modal-card bento-card">
        <h3 style="margin-bottom:1.5rem;">Nouveau dossier</h3>
        <form action="{{ route('folders.store') }}" method="POST">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $currentFolder ? $currentFolder->id : '' }}">
            <div class="form-group">
                <label class="form-label">Nom du dossier</label>
                <input type="text" name="name" class="form-input" placeholder="Mon nouveau dossier" required autofocus>
            </div>
            <div class="flex gap-4" style="justify-content:flex-end; margin-top: 1rem;">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('folder-modal').style.display='none'">Annuler</button>
                <button type="submit" class="btn btn-primary">Créer</button>
            </div>
        </form>
    </div>
</div>

<!-- Share Link Modal -->
<div class="modal-overlay" id="share-modal" style="display:none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px); align-items: center; justify-content: center; z-index: 100;">
    <div class="modal-card bento-card" style="width: 100%; max-width: 450px;">
        <h3 style="margin-bottom:0.5rem;" id="share-modal-title">Partager un élément</h3>
        <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1.5rem;">Générez un lien public sécurisé pour partager cet élément.</p>
        
        <form action="{{ route('shares.store') }}" method="POST">
            @csrf
            <input type="hidden" name="file_id" id="share-file-id">
            <input type="hidden" name="folder_id" id="share-folder-id">
            
            <div class="form-group">
                <label class="form-label">Mot de passe d'accès (Optionnel)</label>
                <input type="password" name="password" class="form-input" placeholder="Laisser vide pour un accès libre">
            </div>

            <div class="form-group">
                <label class="form-label">Date d'expiration (Optionnel)</label>
                <input type="datetime-local" name="expires_at" class="form-input">
            </div>

            <div class="flex gap-4" style="justify-content:flex-end; margin-top: 1.5rem;">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('share-modal').style.display='none'">Annuler</button>
                <button type="submit" class="btn btn-primary">Générer le lien</button>
            </div>
        </form>
    </div>
</div>

<!-- Trash Banner -->
<div class="trash-banner" style="margin-top: 2rem;">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
    <span>Gerez vos fichiers supprimés dans la corbeille</span>
    <a href="{{ route('files.trash') }}" class="btn btn-outline" style="padding:0.4rem 1rem; font-size:0.85rem; margin-left:auto;">Voir la corbeille</a>
</div>

<script>
function openShareModal(fileId, folderId, itemName) {
    document.getElementById('share-file-id').value = fileId || '';
    document.getElementById('share-folder-id').value = folderId || '';
    document.getElementById('share-modal-title').textContent = 'Partager "' + itemName + '"';
    document.getElementById('share-modal').style.display = 'flex';
}
</script>
@endsection
