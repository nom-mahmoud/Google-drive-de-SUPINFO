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
        <p style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 1rem;">
            {{ number_format($file->size / 1024, 1) }} Ko • Partagé par {{ $shareLink->user->firstname }}
        </p>

        @if($shareLink->expires_at)
            @php
                $diffInMinutes = now()->diffInMinutes($shareLink->expires_at, false);
            @endphp
            @if($diffInMinutes > 0)
                <div style="background: rgba(245, 158, 11, 0.1); border: 1px solid #F59E0B; color: #D97706; padding: 0.4rem 0.8rem; border-radius: var(--radius-md); font-size: 0.75rem; display: inline-flex; align-items: center; gap: 0.35rem; margin-bottom: 1.25rem; font-weight: 500; text-align: left;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Expire dans : 
                    @if($diffInMinutes < 60)
                        {{ $diffInMinutes }} min
                    @else
                        {{ floor($diffInMinutes / 60) }}h {{ $diffInMinutes % 60 > 0 ? ($diffInMinutes % 60) . 'm' : '' }}
                    @endif
                </div>
            @endif
        @endif

        <a href="{{ route('shares.download', [$shareLink->token, $file->id]) }}" class="btn btn-primary w-full">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px; vertical-align:middle;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Télécharger
        </a>
    </div>

    <!-- Preview Area Mobile (Images, PDFs, etc.) -->
    @if(Str::contains($file->mime_type, 'image') || Str::contains($file->mime_type, 'pdf') || Str::contains($file->mime_type, 'text') || Str::contains($file->mime_type, 'word'))
    <div class="bento-card" style="padding: 1rem; border-radius: var(--radius-md); margin-top: 1.5rem;">
        <h4 style="font-size: 0.95rem; margin-bottom: 0.75rem;">Aperçu</h4>
        <div style="background: var(--bg-color); padding: 0.5rem; border-radius: var(--radius-sm); text-align: center; display: flex; align-items: center; justify-content: center; overflow: hidden;">
            @if(Str::contains($file->mime_type, 'image'))
                <img src="{{ route('shares.preview', [$shareLink->token, $file->id]) }}" style="max-width: 100%; max-height: 400px; border-radius: var(--radius-sm); object-fit: contain;">
            @elseif(Str::contains($file->mime_type, 'pdf'))
                <iframe src="{{ route('shares.preview', [$shareLink->token, $file->id]) }}" style="width: 100%; height: 350px; border: none; border-radius: var(--radius-sm);"></iframe>
            @else
                <iframe src="{{ route('shares.preview', [$shareLink->token, $file->id]) }}" style="width: 100%; height: 350px; border: none; border-radius: var(--radius-sm); background: #fff;"></iframe>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
