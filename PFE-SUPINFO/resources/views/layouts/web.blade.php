<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUPFile - @yield('title', 'Le Cloud Premium')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ time() }}">
    <script>
        // Apply theme immediately to avoid flash
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark-theme');
        } else {
            document.documentElement.classList.remove('dark-theme');
        }
    </script>
</head>
<body>
    <nav class="web-nav" style="background: var(--surface-color); border-bottom: 1px solid var(--border-color); padding: 1rem 0;">
        <div class="container flex items-center justify-between">
            <a href="/" class="auth-logo" style="margin: 0; font-size: 1.5rem;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                SUPFile
            </a>
            <div class="flex gap-4 items-center">
                <button id="theme-toggle" class="btn btn-outline" style="padding: 0.5rem; border-radius: 50%; width: 40px; height: 40px;">
                    <svg id="sun-icon" style="display:none" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                    <svg id="moon-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                </button>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-outline" style="padding: 0.5rem 1rem;">Tableau de bord</a>
                    <a href="{{ route('settings') }}" class="btn btn-outline" style="padding: 0.5rem 1rem;">Profil</a>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem;">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline" style="padding: 0.5rem 1rem;">Connexion</a>
                    <a href="{{ route('register') }}" class="btn btn-primary" style="padding: 0.5rem 1rem;">Créer un compte</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="container mt-4 mb-6" style="padding-top: 2rem;">
        @yield('content')
    </main>

    <!-- Global Custom Toast -->
    <div id="custom-toast" style="display: none; position: fixed; bottom: 2rem; right: 2rem; background: var(--surface-color); color: var(--text-main); border: 1px solid var(--border-color); padding: 1rem 1.5rem; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); z-index: 10000; align-items: center; gap: 0.75rem; transition: opacity 0.3s ease, transform 0.3s ease; opacity: 0; transform: translateY(10px);">
        <div style="background: rgba(37, 99, 235, 0.1); width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary);">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
        </div>
        <span id="custom-toast-message" style="font-size: 0.9rem; font-weight: 500;">Lien copié !</span>
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
                toast.style.transform = 'translateY(0)';
            }, 10);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 300);
            }, 3000);
        }
    }
    </script>

    <!-- JS -->
    <script src="{{ asset('js/app.js') }}?v={{ time() }}"></script>
</body>
</html>
