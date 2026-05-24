@extends('layouts.mobile')
@section('title', 'Corbeille')
@section('header_title', 'Corbeille')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0;">Les éléments supprimés restent ici avant d'être définitivement effacés.</p>
</div>

<div class="mobile-list" style="display: flex; flex-direction: column; gap: 1rem;">
    @if($deletedFolders->isEmpty() && $deletedFiles->isEmpty())
        <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted); background: var(--surface-color); border-radius: var(--radius-md); border: 1px dashed var(--border-color);">
            <p style="font-size: 0.9rem; margin: 0;">La corbeille est vide.</p>
        </div>
    @endif

    <!-- Dossiers Supprimés -->
    @foreach($deletedFolders as $folder)
    <div class="mobile-list-item" style="opacity: 0.8; background: var(--surface-color); padding: 1rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); display: flex; align-items: center; gap: 1rem;">
        <svg class="file-icon folder-icon" viewBox="0 0 24 24" fill="currentColor" style="width: 36px; height: 36px; color: #FBBF24; opacity: 0.6;"><path d="M10 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/></svg>
        <div class="file-info" style="flex: 1; min-width: 0;">
            <div class="file-name" style="font-weight: 600; font-size: 0.95rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $folder->name }}</div>
            <div class="file-meta" style="font-size: 0.75rem; color: var(--text-muted);">Supprimé le {{ $folder->deleted_at->format('d/m/Y') }}</div>
        </div>
        <div style="display: flex; gap: 0.75rem;" onclick="event.stopPropagation()">
            <form action="{{ route('folders.restore', $folder->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="action-btn" title="Restaurer" style="background: none; border: none; padding: 0; color: var(--primary); display: flex; align-items: center; justify-content: center; width: 32px; height: 32px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"></polyline><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg>
                </button>
            </form>
            <form action="{{ route('folders.force-delete', $folder->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer définitivement ce dossier et tout son contenu ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn" title="Supprimer définitivement" style="background: none; border: none; padding: 0; color: var(--danger); display: flex; align-items: center; justify-content: center; width: 32px; height: 32px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </button>
            </form>
        </div>
    </div>
    @endforeach

    <!-- Fichiers Supprimés -->
    @foreach($deletedFiles as $file)
    <div class="mobile-list-item" style="opacity: 0.8; background: var(--surface-color); padding: 1rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); display: flex; align-items: center; gap: 1rem;">
        @php
            $iconColor = '#94A3B8';
            if (Str::contains($file->mime_type, 'image')) $iconColor = '#10B981';
            elseif (Str::contains($file->mime_type, 'pdf')) $iconColor = '#EF4444';
            elseif (Str::contains($file->mime_type, 'word')) $iconColor = '#2563EB';
            elseif (Str::contains($file->mime_type, 'audio')) $iconColor = '#8B5CF6';
            elseif (Str::contains($file->mime_type, 'video')) $iconColor = '#EC4899';
        @endphp
        <svg class="file-icon" viewBox="0 0 24 24" fill="none" stroke="{{ $iconColor }}" stroke-width="1.5" style="width: 36px; height: 36px; opacity: 0.6;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        <div class="file-info" style="flex: 1; min-width: 0;">
            <div class="file-name" style="font-weight: 600; font-size: 0.95rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $file->name }}</div>
            <div class="file-meta" style="font-size: 0.75rem; color: var(--text-muted);">Supprimé le {{ $file->deleted_at->format('d/m/Y') }}</div>
        </div>
        <div style="display: flex; gap: 0.75rem;" onclick="event.stopPropagation()">
            <form action="{{ route('files.restore', $file->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="action-btn" title="Restaurer" style="background: none; border: none; padding: 0; color: var(--primary); display: flex; align-items: center; justify-content: center; width: 32px; height: 32px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"></polyline><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg>
                </button>
            </form>
            <form action="{{ route('files.force-delete', $file->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer définitivement ce fichier ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn" title="Supprimer définitivement" style="background: none; border: none; padding: 0; color: var(--danger); display: flex; align-items: center; justify-content: center; width: 32px; height: 32px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>

<div style="margin-top: 2rem;">
    <a href="{{ route('files.index') }}" class="btn btn-outline w-full" style="padding: 0.75rem;">Retour aux fichiers</a>
</div>
@endsection
