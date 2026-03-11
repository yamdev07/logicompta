<?php

use Illuminate\Support\Facades\Route;

// Route principale vers la page d'accueil
Route::get('/', function () {
    return view('home');
});

// Route pour la page de connexion
Route::get('/login', function () {
    return view('login');
});

// Route pour la page d'inscription
Route::get('/signup', function () {
    return view('signup');
});

// Route pour la page de mot de passe oublié
Route::get('/forgot-password', function () {
    return view('forgot-password');
});

// Route pour la page de profil
Route::get('/profile', function () {
    return view('profile');
});

// Route pour la page de réinitialisation de mot de passe
Route::get('/reset-password', function () {
    return view('reset-password');
});
