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
        <form action="{{ route('folders.destroy', $folder) }}" method="POST" onsubmit="return confirm('Supprimer ce dossier ?')" onclick="event.stopPropagation()">
            @csrf
            @method('DELETE')
            <button type="submit" class="action-btn" style="background: none; box-shadow: none;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-danger"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg></button>
        </form>
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
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('files.download', $file) }}" class="action-btn" style="background: none; box-shadow: none;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></a>
            <form action="{{ route('files.destroy', $file) }}" method="POST" onsubmit="return confirm('Supprimer ce fichier ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn" style="background: none; box-shadow: none;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-danger"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg></button>
            </form>
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

@endsection
