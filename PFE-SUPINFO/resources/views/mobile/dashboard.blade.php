@extends('layouts.mobile')
@section('title', 'Tableau de Bord')
@section('header_title', 'Mon Espace')

@section('content')
<!-- Quota Usage Card Mobile -->
<div class="bento-card" style="padding: 1.25rem; margin-bottom: 1.5rem; border-radius: var(--radius-md);">
    <h3 style="font-size: 1.1rem; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--primary);"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
        Stockage Utilisé
    </h3>
    <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1rem;">
        <strong>{{ $spaceUsedFormatted }}</strong> sur {{ $quotaMaxFormatted }} ({{ $quotaPercentage }}%)
    </p>
    <div class="upload-progress-bar" style="height: 10px; background-color: var(--border-color); border-radius: 5px; overflow: hidden; margin-bottom: 0.5rem;">
        <div class="upload-progress-fill" style="width: {{ $quotaPercentage }}%; height: 100%; background: linear-gradient(90deg, var(--primary), #10B981);"></div>
    </div>
</div>

<!-- Stats Counter Grid Mobile -->
<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
    <div style="background: var(--surface-color); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); text-align: center; box-shadow: var(--shadow-sm);">
        <div style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">{{ $foldersCount }}</div>
        <div style="font-size: 0.8rem; color: var(--text-muted);">Dossiers</div>
    </div>
    <div style="background: var(--surface-color); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); text-align: center; box-shadow: var(--shadow-sm);">
        <div style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">{{ $filesCount }}</div>
        <div style="font-size: 0.8rem; color: var(--text-muted);">Fichiers</div>
    </div>
    <div style="background: var(--surface-color); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); text-align: center; box-shadow: var(--shadow-sm);">
        <div style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">{{ $sharesCount }}</div>
        <div style="font-size: 0.8rem; color: var(--text-muted);">Partages</div>
    </div>
    <a href="{{ route('files.trash') }}" style="background: var(--surface-color); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); text-align: center; box-shadow: var(--shadow-sm); display: block; color: inherit;">
        <div style="font-size: 1.5rem; font-weight: 700; color: var(--danger);">{{ $trashCount }}</div>
        <div style="font-size: 0.8rem; color: var(--text-muted);">Corbeille</div>
    </a>
</div>

<!-- Recent Files Mobile -->
<div class="bento-card" style="padding: 1.25rem; border-radius: var(--radius-md);">
    <h3 style="font-size: 1.1rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        Récents
    </h3>
    
    @if($recentFiles->isEmpty())
        <p style="text-align: center; color: var(--text-muted); font-size: 0.85rem; padding: 1.5rem 0;">Aucun fichier récent.</p>
    @else
        <div class="mobile-list">
            @foreach($recentFiles as $file)
                <div class="mobile-list-item" style="padding: 0.75rem; border-radius: var(--radius-sm); margin-bottom: 0.5rem; border: 1px solid var(--border-color); background: var(--bg-color);">
                    @php
                        $iconColor = '#6B7280';
                        if (Str::contains($file->mime_type, 'image')) $iconColor = '#10B981';
                        elseif (Str::contains($file->mime_type, 'pdf')) $iconColor = '#EF4444';
                    @endphp
                    <svg class="file-icon" viewBox="0 0 24 24" fill="none" stroke="{{ $iconColor }}" stroke-width="1.5" style="width: 32px; height: 32px;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <div class="file-info" style="margin-left: 0.5rem;">
                        <div class="file-name" style="font-size: 0.9rem; font-weight: 500;">{{ $file->name }}</div>
                        <div class="file-meta" style="font-size: 0.75rem;">{{ number_format($file->size / 1024, 1) }} Ko</div>
                    </div>
                    <a href="{{ route('files.download', $file) }}" class="action-btn" style="background: none; box-shadow: none;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
