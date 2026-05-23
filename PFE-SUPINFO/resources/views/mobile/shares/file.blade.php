@extends('layouts.mobile')
@section('title', 'Fichier Partagé')
@section('header_title', 'Aperçu Fichier')

@section('content')
<div style="padding: 1rem 0;">
    <div class="bento-card text-center" style="padding: 1.5rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
        @php
            $iconColor = '#6B7280';
            if (Str::contains($file->mime_type, 'image')) $iconColor = '#10B981';
            elseif (Str::contains($file->mime_type, 'pdf')) $iconColor = '#EF4444';
        @endphp
        <svg class="file-icon" viewBox="0 0 24 24" fill="none" stroke="{{ $iconColor }}" stroke-width="1.5" style="width: 64px; height: 64px; margin-bottom: 1rem;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        
        <h3 class="text-truncate" style="font-size: 1.2rem; margin-bottom: 0.25rem;">{{ $file->name }}</h3>
        <p style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 1.5rem;">
            {{ number_format($file->size / 1024, 1) }} Ko • Partagé par {{ $shareLink->user->firstname }}
        </p>

        <a href="{{ route('shares.download', [$shareLink->token, $file->id]) }}" class="btn btn-primary w-full">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px; vertical-align:middle;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Télécharger
        </a>
    </div>

    <!-- Preview Area Mobile (Only Images) -->
    @if(Str::contains($file->mime_type, 'image'))
    <div class="bento-card" style="padding: 1rem; border-radius: var(--radius-md);">
        <h4 style="font-size: 0.95rem; margin-bottom: 0.75rem;">Aperçu</h4>
        <div style="background: var(--bg-color); padding: 0.5rem; border-radius: var(--radius-sm); text-align: center;">
            <img src="{{ route('shares.download', [$shareLink->token, $file->id]) }}" style="max-width: 100%; border-radius: var(--radius-sm);">
        </div>
    </div>
    @endif
</div>
@endsection
