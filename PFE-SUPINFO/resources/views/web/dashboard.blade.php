@extends('layouts.web')
@section('title', 'Tableau de Bord')

@section('content')
<div class="file-manager-header" style="margin-bottom: 2.5rem;">
    <div>
        <h1 class="page-title">Tableau de bord</h1>
        <p style="color: var(--text-muted);">Bonjour, {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}. Gérez votre stockage et vos partages ici.</p>
    </div>
    <div class="file-manager-actions">
        <a href="{{ route('files.index') }}" class="btn btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
            Accéder à l'explorateur
        </a>
    </div>
</div>

<!-- Bento Grid Layout -->
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; margin-bottom: 2rem;">
    
    <!-- Quota Card (Large - span 2 columns) -->
    <div class="bento-card" style="grid-column: span 2; display: flex; flex-direction: column; justify-content: space-between;">
        <div>
            <h3 style="margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--primary);"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
                Utilisation du Stockage
            </h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">Vous utilisez <strong>{{ $spaceUsedFormatted }}</strong> de votre quota total de {{ $quotaMaxFormatted }} ({{ $quotaPercentage }}%).</p>
        </div>
        
        <div>
            <div class="upload-progress-bar" style="height: 16px; background-color: var(--border-color); border-radius: 8px; margin-bottom: 1rem; overflow: hidden;">
                <div class="upload-progress-fill" style="width: {{ $quotaPercentage }}%; height: 100%; background: linear-gradient(90deg, var(--primary), #10B981);"></div>
            </div>
            
            <div style="display: flex; justify-content: space-between; font-size: 0.85rem; color: var(--text-muted);">
                <span>0 Go</span>
                <span>{{ $quotaMaxFormatted }}</span>
            </div>
        </div>
    </div>

    <!-- Quick Stats Card (Vertical stack) -->
    <div class="bento-card" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; padding: 1.5rem;">
        <div style="background: var(--bg-color); padding: 1rem; border-radius: var(--radius-md); text-align: center; border: 1px solid var(--border-color);">
            <div style="font-size: 1.75rem; font-weight: 700; color: var(--primary);">{{ $foldersCount }}</div>
            <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 500;">Dossiers</div>
        </div>
        <div style="background: var(--bg-color); padding: 1rem; border-radius: var(--radius-md); text-align: center; border: 1px solid var(--border-color);">
            <div style="font-size: 1.75rem; font-weight: 700; color: var(--primary);">{{ $filesCount }}</div>
            <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 500;">Fichiers</div>
        </div>
        <div style="background: var(--bg-color); padding: 1rem; border-radius: var(--radius-md); text-align: center; border: 1px solid var(--border-color);">
            <div style="font-size: 1.75rem; font-weight: 700; color: var(--primary);">{{ $sharesCount }}</div>
            <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 500;">Partages</div>
        </div>
        <a href="{{ route('files.trash') }}" style="background: var(--bg-color); padding: 1rem; border-radius: var(--radius-md); text-align: center; border: 1px solid var(--border-color); display: block; color: inherit;">
            <div style="font-size: 1.75rem; font-weight: 700; color: var(--danger);">{{ $trashCount }}</div>
            <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 500;">Corbeille</div>
        </a>
    </div>

</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
    
    <!-- File Types Breakdown -->
    <div class="bento-card" style="padding: 1.5rem;">
        <h3 style="margin-bottom: 1.5rem; font-size: 1.15rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
            Types de fichiers
        </h3>
        
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <!-- Images -->
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: var(--bg-color); border-radius: var(--radius-sm); border-left: 4px solid var(--success);">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    <span style="font-weight: 500; font-size: 0.9rem;">Images</span>
                </div>
                <span style="font-weight: 600; font-size: 0.9rem; color: var(--text-muted);">{{ $imagesCount }}</span>
            </div>
            
            <!-- PDFs -->
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: var(--bg-color); border-radius: var(--radius-sm); border-left: 4px solid var(--danger);">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--danger)" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <span style="font-weight: 500; font-size: 0.9rem;">Documents PDF</span>
                </div>
                <span style="font-weight: 600; font-size: 0.9rem; color: var(--text-muted);">{{ $pdfsCount }}</span>
            </div>

            <!-- Word/Docs -->
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: var(--bg-color); border-radius: var(--radius-sm); border-left: 4px solid var(--primary);">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    <span style="font-weight: 500; font-size: 0.9rem;">Documents / Texte</span>
                </div>
                <span style="font-weight: 600; font-size: 0.9rem; color: var(--text-muted);">{{ $docsCount }}</span>
            </div>

            <!-- Others -->
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: var(--bg-color); border-radius: var(--radius-sm); border-left: 4px solid var(--text-muted);">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <span style="font-weight: 500; font-size: 0.9rem;">Autres</span>
                </div>
                <span style="font-weight: 600; font-size: 0.9rem; color: var(--text-muted);">{{ $othersCount }}</span>
            </div>
        </div>
    </div>
    
    <!-- Recent Uploads -->
    <div class="bento-card" style="padding: 1.5rem;">
        <h3 style="margin-bottom: 1.5rem; font-size: 1.15rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--primary);"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Fichiers récents
        </h3>
        
        @if($recentFiles->isEmpty())
            <div style="text-align: center; padding: 2rem; color: var(--text-muted);">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 0.5rem; opacity: 0.5;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <p style="font-size: 0.9rem;">Aucun fichier récent trouvé.</p>
            </div>
        @else
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @foreach($recentFiles as $file)
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem; background: var(--bg-color); border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                        <div style="display: flex; align-items: center; gap: 0.75rem; min-width: 0;">
                            @php
                                $iconColor = '#6B7280';
                                if (Str::contains($file->mime_type, 'image')) $iconColor = '#10B981';
                                elseif (Str::contains($file->mime_type, 'pdf')) $iconColor = '#EF4444';
                                elseif (Str::contains($file->mime_type, 'word')) $iconColor = '#2563EB';
                            @endphp
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="{{ $iconColor }}" stroke-width="2" style="flex-shrink: 0;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            <div style="min-width: 0;">
                                <div class="text-truncate" style="font-weight: 500; font-size: 0.9rem;" title="{{ $file->name }}">{{ $file->name }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ number_format($file->size / 1024, 1) }} Ko • {{ $file->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        <div style="display: flex; gap: 0.5rem; flex-shrink: 0;">
                            <a href="{{ route('files.download', $file) }}" class="btn btn-outline" style="padding: 0.4rem 0.6rem; font-size: 0.8rem; border-radius: var(--radius-sm);">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
