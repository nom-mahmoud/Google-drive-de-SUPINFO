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
    <div style="font-size: 0.9rem; color: var(--text-muted); display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem;">
        <div>Partagé par : <strong>{{ $shareLink->user->firstname }} {{ $shareLink->user->lastname }}</strong></div>
        @if($shareLink->expires_at)
            @php
                $diffInMinutes = now()->diffInMinutes($shareLink->expires_at, false);
            @endphp
            @if($diffInMinutes > 0)
                <span style="background: rgba(245, 158, 11, 0.1); border: 1px solid #F59E0B; color: #D97706; padding: 0.25rem 0.75rem; border-radius: var(--radius-md); font-size: 0.8rem; font-weight: 500; display: inline-flex; align-items: center; gap: 0.35rem;">
                    ⏳ Expire dans : 
                    @if($diffInMinutes < 60)
                        {{ $diffInMinutes }} min
                    @else
                        {{ floor($diffInMinutes / 60) }}h {{ $diffInMinutes % 60 > 0 ? ($diffInMinutes % 60) . 'm' : '' }}
                    @endif
                </span>
            @endif
        @endif
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
            <button type="button" class="action-btn" title="Aperçu" data-url="{{ route('shares.preview', [$shareLink->token, $file->id]) }}" data-mime="{{ $file->mime_type }}" data-name="{{ $file->name }}" onclick="triggerPreview(this)"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
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

<!-- Preview Modal -->
<div class="modal-overlay" id="preview-modal" style="display:none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(4px); align-items: center; justify-content: center; z-index: 9999;">
    <div class="modal-card bento-card" style="width: 90%; max-width: 800px; height: 80%; display: flex; flex-direction: column; padding: 1.5rem; background: var(--bg-card); border-radius: 1rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 id="preview-modal-title" style="margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 80%;">Aperçu du fichier</h3>
            <button onclick="closePreviewModal()" class="btn btn-outline" style="padding: 0.25rem 0.5rem; min-width: auto; height: auto;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div id="preview-modal-body" style="flex: 1; overflow: auto; display: flex; align-items: center; justify-content: center; background: #000; border-radius: 0.5rem;">
            <!-- Content injected dynamically -->
        </div>
    </div>
</div>

<script>
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('active'), 10);
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove('active');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }
}

function triggerPreview(btn) {
    openPreviewModal(btn.getAttribute('data-url'), btn.getAttribute('data-mime'), btn.getAttribute('data-name'));
}

function openPreviewModal(url, mimeType, name) {
    const title = document.getElementById('preview-modal-title');
    const body = document.getElementById('preview-modal-body');
    title.textContent = name;
    body.innerHTML = '';

    if (mimeType.includes('image')) {
        const img = document.createElement('img');
        img.src = url;
        img.style.maxWidth = '100%';
        img.style.maxHeight = '100%';
        img.style.objectFit = 'contain';
        body.appendChild(img);
    } else if (mimeType.includes('pdf')) {
        const iframe = document.createElement('iframe');
        iframe.src = url;
        iframe.style.width = '100%';
        iframe.style.height = '100%';
        iframe.style.border = 'none';
        body.appendChild(iframe);
    } else {
        const iframe = document.createElement('iframe');
        iframe.src = url;
        iframe.style.width = '100%';
        iframe.style.height = '100%';
        iframe.style.border = 'none';
        iframe.style.background = '#fff';
        body.appendChild(iframe);
    }
    
    openModal('preview-modal');
}

function closePreviewModal() {
    closeModal('preview-modal');
    setTimeout(() => {
        document.getElementById('preview-modal-body').innerHTML = '';
    }, 300);
}
</script>
@endsection
