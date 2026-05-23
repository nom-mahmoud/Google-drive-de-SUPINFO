@extends('layouts.web')
@section('title', 'Dossier Partagé')

@section('content')
<div class="file-manager-header" style="margin-bottom: 2rem;">
    <div>
        <h1 class="page-title">Explorateur Public</h1>
        <nav class="breadcrumb">
            @foreach($breadcrumbs as $breadcrumb)
                @if($loop->first)
                    <a href="{{ route('shares.public', $shareLink->token) }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px; vertical-align:text-bottom;"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                        {{ $breadcrumb->name }}
                    </a>
                @else
                    <span class="separator">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                    </span>
                    <a href="{{ route('shares.public', [$shareLink->token, 'subfolder_id' => $breadcrumb->id]) }}">{{ $breadcrumb->name }}</a>
                @endif
            @endforeach
        </nav>
    </div>
    <div style="font-size: 0.9rem; color: var(--text-muted);">
        Partagé par : <strong>{{ $shareLink->user->firstname }} {{ $shareLink->user->lastname }}</strong>
    </div>
</div>

<!-- File Grid -->
<div class="file-grid">
    @if($folders->isEmpty() && $files->isEmpty())
        <div style="grid-column: 1 / -1; text-align: center; padding: 4rem; color: var(--text-muted);">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="margin-bottom: 1rem; opacity: 0.5;"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
            <p>Ce dossier est vide.</p>
        </div>
    @endif

    <!-- Dossiers -->
    @foreach($folders as $subfolder)
    <div class="file-card" onclick="window.location='{{ route('shares.public', [$shareLink->token, 'subfolder_id' => $subfolder->id]) }}'">
        <svg class="file-icon folder-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M10 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/></svg>
        <div class="file-name">{{ $subfolder->name }}</div>
        <div class="file-meta">{{ $subfolder->updated_at->diffForHumans() }}</div>
    </div>
    @endforeach

    <!-- Fichiers -->
    @foreach($files as $file)
    <div class="file-card" style="cursor: default;">
        <div class="file-actions" style="opacity: 1;">
            <a href="{{ route('shares.download', [$shareLink->token, $file->id]) }}" class="action-btn" title="Télécharger"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></a>
        </div>
        @php
            $iconColor = '#6B7280';
            if (Str::contains($file->mime_type, 'image')) $iconColor = '#10B981';
            elseif (Str::contains($file->mime_type, 'pdf')) $iconColor = '#EF4444';
            elseif (Str::contains($file->mime_type, 'word')) $iconColor = '#2563EB';
        @endphp
        <svg class="file-icon" viewBox="0 0 24 24" fill="none" stroke="{{ $iconColor }}" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        <div class="file-name text-truncate" title="{{ $file->name }}">{{ $file->name }}</div>
        <div class="file-meta">{{ number_format($file->size / 1024, 1) }} Ko</div>
    </div>
    @endforeach
</div>
@endsection
