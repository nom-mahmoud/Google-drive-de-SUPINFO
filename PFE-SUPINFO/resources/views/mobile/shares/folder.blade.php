@extends('layouts.mobile')
@section('title', 'Dossier Partagé')
@section('header_title', 'Dossier Partagé')

@section('content')
<!-- Navigation path mobile -->
<div style="margin-bottom: 1rem; font-size: 0.85rem; color: var(--text-muted); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
    <div>
        @foreach($breadcrumbs as $breadcrumb)
            @if($loop->first)
                <a href="{{ route('shares.public', $shareLink->token) }}">{{ $breadcrumb->name }}</a>
            @else
                / <a href="{{ route('shares.public', [$shareLink->token, 'subfolder_id' => $breadcrumb->id]) }}">{{ $breadcrumb->name }}</a>
            @endif
        @endforeach
    </div>
    @if($shareLink->expires_at)
        @php
            $diffInMinutes = now()->diffInMinutes($shareLink->expires_at, false);
        @endphp
        @if($diffInMinutes > 0)
            <span style="background: rgba(245, 158, 11, 0.1); border: 1px solid #F59E0B; color: #D97706; padding: 0.2rem 0.5rem; border-radius: var(--radius-sm); font-size: 0.75rem; font-weight: 500; display: inline-flex; align-items: center; gap: 0.25rem;">
                ⏳ 
                @if($diffInMinutes < 60)
                    {{ $diffInMinutes }} min
                @else
                    {{ floor($diffInMinutes / 60) }}h {{ $diffInMinutes % 60 > 0 ? ($diffInMinutes % 60) . 'm' : '' }}
                @endif
            </span>
        @endif
    @endif
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
            elseif (Str::contains($file->mime_type, 'word')) $iconColor = '#2563EB';
        @endphp
        <svg class="file-icon" viewBox="0 0 24 24" fill="none" stroke="{{ $iconColor }}" stroke-width="1.5" style="width: 36px; height: 36px; margin-bottom: 0;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        <div class="file-info" style="margin-left: 0.5rem;">
            <div class="file-name" style="font-size: 0.95rem; font-weight: 500;">{{ $file->name }}</div>
            <div class="file-meta" style="font-size: 0.75rem;">{{ number_format($file->size / 1024, 1) }} Ko</div>
        </div>
        <div style="display: flex; gap: 0.25rem; align-items: center;">
            <button type="button" class="action-btn" style="background: none; box-shadow: none; padding: 0.25rem; color: var(--text-muted);" title="Aperçu" data-url="{{ route('shares.preview', [$shareLink->token, $file->id]) }}" data-mime="{{ $file->mime_type }}" data-name="{{ $file->name }}" onclick="triggerPreview(this)">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
            <a href="{{ route('shares.download', [$shareLink->token, $file->id]) }}" class="action-btn" style="background: none; box-shadow: none; padding: 0.25rem; color: var(--text-muted);" title="Télécharger">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            </a>
        </div>
    </div>
    @endforeach
</div>

<!-- Preview Modal -->
<div class="modal-overlay" id="preview-modal" style="display:none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(4px); align-items: center; justify-content: center; z-index: 9999;">
    <div class="modal-card bento-card" style="width: 90%; max-width: 500px; height: 70%; display: flex; flex-direction: column; padding: 1.25rem; background: var(--bg-card); border-radius: 1rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 id="preview-modal-title" style="margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 80%; font-size: 1.1rem;">Aperçu du fichier</h3>
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
