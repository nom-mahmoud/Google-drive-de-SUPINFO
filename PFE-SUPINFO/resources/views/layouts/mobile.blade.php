<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SUPFile - @yield('title', 'Mobile')</title>
    <meta name="theme-color" content="#2563EB">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ time() }}">
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark-theme');
        } else {
            document.documentElement.classList.remove('dark-theme');
        }
    </script>
</head>
<body style="padding-bottom: 80px;"> <!-- Espace pour la barre de navigation du bas -->
    
    <!-- Header Mobile (Optionnel sur certaines vues) -->
    @hasSection('header_title')
    <header style="background: var(--surface-color); padding: 1rem; border-bottom: 1px solid var(--border-color); position: sticky; top: 0; z-index: 40;">
        <div class="flex items-center justify-between">
            <h1 style="font-size: 1.25rem; margin: 0; display: flex; align-items: center; gap: 0.5rem; color: var(--primary);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                @yield('header_title')
            </h1>
            <div class="flex items-center gap-2">
                <button id="theme-toggle-mobile" class="btn btn-outline" style="padding: 0.4rem; border-radius: 50%; width: 36px; height: 36px; border: none;">
                    <svg class="sun-icon" style="display:none" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                    <svg class="moon-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                </button>
                @yield('header_actions')
            </div>
        </div>
    </header>
    @endif

    <main style="padding: 1rem;">
        @yield('content')
    </main>

    @auth
    <!-- Barre de navigation basse (Mobile) -->
    <nav class="mobile-bottom-nav">
        <a href="{{ route('dashboard') }}" class="nav-item {{ Route::is('dashboard') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
            <span>Accueil</span>
        </a>
        <a href="{{ route('files.index') }}" class="nav-item {{ Route::is('files.index') || Route::is('files.trash') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
            <span>Fichiers</span>
        </a>
        <a href="{{ route('settings') }}" class="nav-item {{ Route::is('settings') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
            <span>Profil</span>
        </a>
    </nav>
    @endauth

    <!-- Global Custom Toast Mobile -->
    <div id="custom-toast" style="display: none; position: fixed; top: 1.5rem; left: 50%; transform: translateX(-50%) translateY(-10px); background: var(--surface-color); color: var(--text-main); border: 1px solid var(--border-color); padding: 0.75rem 1.25rem; border-radius: 2rem; box-shadow: var(--shadow-lg); z-index: 10000; align-items: center; gap: 0.5rem; transition: opacity 0.3s ease, transform 0.3s ease; opacity: 0; width: 85%; max-width: 320px; justify-content: center; box-sizing: border-box;">
        <div style="background: rgba(37, 99, 235, 0.1); width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary); flex-shrink: 0;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
        </div>
        <span id="custom-toast-message" style="font-size: 0.85rem; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Lien copié !</span>
    </div>

    <script>
    function showToast(message) {
        const toast = document.getElementById('custom-toast');
        const msgSpan = document.getElementById('custom-toast-message');
        if (toast && msgSpan) {
            msgSpan.textContent = message;
            toast.style.display = 'flex';
            setTimeout(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateX(-50%) translateY(0)';
            }, 10);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(-50%) translateY(-10px)';
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 300);
            }, 3000);
        }
    }
    </script>

    <script src="{{ asset('js/app.js') }}?v={{ time() }}"></script>
</body>
</html>
