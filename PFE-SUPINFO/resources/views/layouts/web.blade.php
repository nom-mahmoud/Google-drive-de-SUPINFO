<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUPFile - @yield('title', 'Le Cloud Premium')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <nav class="web-nav" style="background: var(--surface-color); border-bottom: 1px solid var(--border-color); padding: 1rem 0;">
        <div class="container flex items-center justify-between">
            <a href="/" class="auth-logo" style="margin: 0; font-size: 1.5rem;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                SUPFile
            </a>
            <div class="flex gap-4 items-center">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-outline" style="padding: 0.5rem 1rem;">Tableau de bord</a>
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

    <!-- JS -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
