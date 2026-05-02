@extends('layouts.mobile')

@section('title', 'Inscription')

@section('header_title', 'Créer un compte')
@section('header_actions')
    <a href="{{ route('login') ?? '#' }}" style="font-size: 0.875rem; font-weight: 500;">Connexion</a>
@endsection

@section('content')
<div class="mt-4 mb-6">
    <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">Commencez ici</h2>
    <p class="text-muted" style="font-size: 0.875rem;">30 Go de stockage cloud premium gratuit.</p>
</div>

<form action="{{ route('register.post') ?? '#' }}" method="POST">
    @csrf
    
    <div class="form-group">
        <label for="firstname" class="form-label">Prénom</label>
        <input type="text" id="firstname" name="firstname" class="form-input" placeholder="Jean" required style="padding: 1rem;">
    </div>
    
    <div class="form-group">
        <label for="lastname" class="form-label">Nom</label>
        <input type="text" id="lastname" name="lastname" class="form-input" placeholder="Dupont" required style="padding: 1rem;">
    </div>

    <div class="form-group">
        <label for="email" class="form-label">Adresse Email</label>
        <input type="email" id="email" name="email" class="form-input" placeholder="vous@exemple.com" required style="padding: 1rem;">
    </div>
    
    <div class="form-group">
        <label for="password" class="form-label">Mot de passe</label>
        <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required style="padding: 1rem;">
    </div>

    <div class="form-group">
        <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="••••••••" required style="padding: 1rem;">
    </div>

    <button type="submit" class="btn btn-primary w-full mt-4" style="padding: 1rem; font-size: 1.125rem;">M'inscrire maintenant</button>
</form>

<div class="mt-6 text-center text-muted" style="font-size: 0.75rem;">
    En vous inscrivant, vous acceptez nos Conditions d'utilisation et notre Politique de confidentialité.
</div>
@endsection
