@extends('layouts.mobile')
@section('title', 'Dossier Partagé')
@section('header_title', 'Dossier Partagé')

@section('content')
<!-- Navigation path mobile -->
<div style="margin-bottom: 1rem; font-size: 0.85rem; color: var(--text-muted);">
    @foreach($breadcrumbs as $breadcrumb)
        @if($loop->first)
            <a href="{{ route('shares.public', $shareLink->token) }}">{{ $breadcrumb->name }}</a>
        @else
            / <a href="{{ route('shares.public', [$shareLink->token, 'subfolder_id' => $breadcrumb->id]) }}">{{ $breadcrumb->name }}</a>
        @endif
    @endforeach
</div>

<div class="mobile-list">
    @if($folders->isEmpty() && $files->isEmpty())
        <p style="text-align: center; color: var(--text-muted); padding: 2rem;">Ce dossier est vide.</p>
    @endif

    <!-- Folders list -->
    @foreach($folders as $subfolder)
    <div class="mobile-list-item" onclick="window.location='{{ route('shares.public', [$shareLink->token, 'subfolder_id' => $subfolder->id]) }}'">
        <svg class="file-icon folder-icon" viewBox="0 0 24 24" fill="currentColor" style="width: 36px; height: 36px; margin-bottom: 0;"><path d="M10 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/></svg>
        <div class="file-info" style="margin-left: 0.5rem;">
            <div class="file-name" style="font-size: 0.95rem; font-weight: 500;">{{ $subfolder->name }}</div>
            <div class="file-meta" style="font-size: 0.75rem;">Dossier</div>
        </div>
    </div>
    @endforeach

    <!-- Files list -->
    @foreach($files as $file)
    <div class="mobile-list-item" style="cursor: default;">
        @php
            $iconColor = '#6B7280';
            if (Str::contains($file->mime_type, 'image')) $iconColor = '#10B981';
            elseif (Str::contains($file->mime_type, 'pdf')) $iconColor = '#EF4444';
        @endphp
        <svg class="file-icon" viewBox="0 0 24 24" fill="none" stroke="{{ $iconColor }}" stroke-width="1.5" style="width: 36px; height: 36px; margin-bottom: 0;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        <div class="file-info" style="margin-left: 0.5rem;">
            <div class="file-name" style="font-size: 0.95rem; font-weight: 500;">{{ $file->name }}</div>
            <div class="file-meta" style="font-size: 0.75rem;">{{ number_format($file->size / 1024, 1) }} Ko</div>
        </div>
        <a href="{{ route('shares.download', [$shareLink->token, $file->id]) }}" class="action-btn" style="background: none; box-shadow: none;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></a>
    </div>
    @endforeach
</div>
@endsection
