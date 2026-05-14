@extends('layouts.web')
@section('title', 'Corbeille')

@section('content')
<div class="file-manager-header">
    <div>
        <h1 class="page-title">Corbeille</h1>
        <p style="color: var(--text-muted);">Les éléments supprimés restent ici avant d'être définitivement effacés.</p>
    </div>
    <div class="file-manager-actions">
        <a href="{{ route('files.index') }}" class="btn btn-outline">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><polyline points="15 18 9 12 15 6"></polyline></svg>
            Retour aux fichiers
        </a>
    </div>
</div>

<div class="file-grid" style="margin-top: 2rem;">
    @if($deletedFolders->isEmpty() && $deletedFiles->isEmpty())
        <div style="grid-column: 1 / -1; text-align: center; padding: 4rem; color: var(--text-muted);">
            <p>La corbeille est vide.</p>
        </div>
    @endif

    @foreach($deletedFolders as $folder)
    <div class="file-card" style="opacity: 0.7;">
        <div class="file-actions">
            <form action="{{ route('folders.restore', $folder->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="action-btn" title="Restaurer" style="color: var(--primary-color);">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"></polyline><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg>
                </button>
            </form>
        </div>
        <svg class="file-icon folder-icon" viewBox="0 0 24 24" fill="currentColor" style="opacity: 0.5;"><path d="M10 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/></svg>
        <div class="file-name">{{ $folder->name }}</div>
        <div class="file-meta">Supprimé le {{ $folder->deleted_at->format('d/m/Y') }}</div>
    </div>
    @endforeach

    @foreach($deletedFiles as $file)
    <div class="file-card" style="opacity: 0.7;">
        <div class="file-actions">
            <form action="{{ route('folders.restore', $file->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="action-btn" title="Restaurer" style="color: var(--primary-color);">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"></polyline><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg>
                </button>
            </form>
        </div>
        <svg class="file-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="opacity: 0.5;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        <div class="file-name">{{ $file->name }}</div>
        <div class="file-meta">Supprimé le {{ $file->deleted_at->format('d/m/Y') }}</div>
    </div>
    @endforeach
</div>
@endsection
