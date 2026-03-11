@extends('layouts.accounting')

@section('title', 'Dashboard')

@section('content')
<div class="flex flex-col gap-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-500 italic">Bienvenue sur votre tableau de bord de gestion.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <a href="{{ route('accounting.journal.index') }}" class="group relative bg-white border border-border rounded-3xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i data-lucide="book-open" class="w-32 h-32 -mr-8 -mt-8 rotate-12"></i>
            </div>
            
            <div class="relative z-10">
                <div class="w-14 h-14 bg-primary/10 text-primary rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i data-lucide="book-open" class="w-7 h-7"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Comptabilité Générale</h3>
                <p class="text-sm text-gray-500 leading-relaxed mb-6">Gérez vos journaux, consultez le grand livre, la balance et éditez vos états financiers (Bilan, Résultat).</p>
                
                <div class="flex items-center text-primary font-bold text-sm gap-2 uppercase tracking-widest">
                    Accéder au module
                    <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
