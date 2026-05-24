@extends('layouts.web')
@section('title', 'Fichier Partagé')

@section('content')
<div class="container flex justify-center" style="margin-top: 2rem;">
    <div style="width: 100%; max-width: 800px;">
        <div class="bento-card text-center" style="margin-bottom: 2rem;">
            @php
                $iconColor = '#6B7280';
                if (Str::contains($file->mime_type, 'image')) $iconColor = '#10B981';
                elseif (Str::contains($file->mime_type, 'pdf')) $iconColor = '#EF4444';
                elseif (Str::contains($file->mime_type, 'word')) $iconColor = '#2563EB';
            @endphp
            <svg class="file-icon" viewBox="0 0 24 24" fill="none" stroke="{{ $iconColor }}" stroke-width="1.5" style="width: 80px; height: 80px; margin-bottom: 1rem;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            
            <h2 class="text-truncate" title="{{ $file->name }}" style="font-size: 1.5rem; margin-bottom: 0.5rem;">{{ $file->name }}</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem;">
                Taille : {{ number_format($file->size / 1024, 1) }} Ko • Partagé par {{ $shareLink->user->firstname }}
            </p>

            @if($shareLink->expires_at)
                @php
                    $diffInMinutes = now()->diffInMinutes($shareLink->expires_at, false);
                @endphp
                @if($diffInMinutes > 0)
                    <div style="background: rgba(245, 158, 11, 0.1); border: 1px solid #F59E0B; color: #D97706; padding: 0.5rem 1rem; border-radius: var(--radius-md); font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem; font-weight: 500;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        ⏳ Expire dans : 
                        @if($diffInMinutes < 60)
                            {{ $diffInMinutes }} minute{{ $diffInMinutes > 1 ? 's' : '' }}
                        @else
                            {{ floor($diffInMinutes / 60) }} heure{{ floor($diffInMinutes / 60) > 1 ? 's' : '' }}{{ $diffInMinutes % 60 > 0 ? ' ' . ($diffInMinutes % 60) . ' min' : '' }}
                        @endif
                    </div>
                @endif
            @endif

            <div style="margin-bottom: 1.5rem;">
                <a href="{{ route('shares.download', [$shareLink->token, $file->id]) }}" class="btn btn-primary" style="padding: 0.75rem 2rem;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Télécharger le fichier
            </a>
        </div>

        <!-- Preview Area -->
        @if(Str::contains($file->mime_type, 'image') || Str::contains($file->mime_type, 'pdf'))
        <div class="bento-card" style="padding: 1.5rem;">
            <h3 style="font-size: 1.15rem; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Aperçu</h3>
            <div style="display: flex; justify-content: center; align-items: center; background: var(--bg-color); padding: 1rem; border-radius: var(--radius-md); overflow: hidden;">
                @if(Str::contains($file->mime_type, 'image'))
                    <img src="{{ route('shares.preview', [$shareLink->token, $file->id]) }}" style="max-width: 100%; max-height: 500px; border-radius: var(--radius-sm); object-fit: contain;">
                @elseif(Str::contains($file->mime_type, 'pdf'))
                    <iframe src="{{ route('shares.preview', [$shareLink->token, $file->id]) }}" style="width: 100%; height: 550px; border: none; border-radius: var(--radius-sm);"></iframe>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
