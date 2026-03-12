@extends('layouts.accounting')

@section('title', 'Dashboard')

@section('content')
<div class="flex flex-col gap-8">

    {{-- Hero Header --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-primary to-primary-light rounded-3xl p-8 text-white shadow-xl">
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <svg viewBox="0 0 400 200" class="w-full h-full"><circle cx="350" cy="50" r="120" fill="white"/><circle cx="50" cy="180" r="80" fill="white"/></svg>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-blue-200 mb-1">Tableau de bord</p>
                <h1 class="text-3xl font-black">Bienvenue, <span id="dash-username">—</span> 👋</h1>
                <p class="text-blue-100 mt-1 text-sm" id="dash-role-text">Chargement de votre profil...</p>
            </div>
            <div id="dash-entreprise-badge" class="hidden bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl px-5 py-3 text-center min-w-[180px]">
                <p class="text-[10px] uppercase font-bold tracking-widest text-blue-200 mb-0.5">Entreprise</p>
                <p class="font-bold text-white" id="dash-entreprise-name">—</p>
                <p class="text-[10px] text-blue-300 mt-0.5 font-mono" id="dash-entreprise-code">—</p>
            </div>
        </div>
    </div>

    {{-- Bandeau Alerte : pas d'entreprise --}}
    <div id="no-entreprise-banner" class="hidden">
        <div class="bg-amber-50 dark:bg-amber-950/30 border-2 border-amber-200 dark:border-amber-700 rounded-2xl p-6">
            <div class="flex flex-col md:flex-row md:items-center gap-6">
                <div class="flex items-start gap-4 flex-1">
                    <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/50 text-amber-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i data-lucide="building-2" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-amber-900 dark:text-amber-200">Aucune entreprise associée</h3>
                        <p class="text-amber-700 dark:text-amber-400 text-sm mt-1">Associez votre compte à une entreprise pour accéder à toutes les fonctionnalités comptables.</p>
                    </div>
                </div>

                {{-- Formulaire rapide de liaison --}}
                <div class="flex flex-col sm:flex-row gap-3 flex-shrink-0">
                    <div class="flex gap-2">
                        <input type="text" id="quick-code" placeholder="Code entreprise..."
                               class="px-4 py-2.5 border-2 border-amber-200 dark:border-amber-700 rounded-xl bg-white dark:bg-amber-950/50 text-gray-800 dark:text-white font-mono text-sm uppercase focus:outline-none focus:border-primary transition-all w-44"
                               oninput="this.value=this.value.toUpperCase()">
                        <button onclick="linkEntreprise()" id="link-btn"
                                class="px-4 py-2.5 bg-primary text-white font-bold rounded-xl hover:bg-primary-light transition-all text-sm flex items-center gap-2 whitespace-nowrap">
                            <i data-lucide="link" class="w-4 h-4"></i> Lier
                        </button>
                    </div>
                    <a href="{{ url('/entreprise-setup') }}"
                       class="px-4 py-2.5 border-2 border-amber-300 dark:border-amber-600 text-amber-800 dark:text-amber-300 font-bold rounded-xl hover:bg-amber-100 dark:hover:bg-amber-900/30 transition-all text-sm text-center flex items-center gap-2 whitespace-nowrap">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i> Créer une entreprise
                    </a>
                </div>
            </div>
            <div id="link-msg" class="hidden mt-3 text-sm font-medium rounded-lg px-4 py-2"></div>
        </div>
    </div>

    {{-- Modules --}}
    <div>
        <p class="text-xs uppercase font-bold tracking-widest text-gray-400 mb-4">Modules disponibles</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <a href="{{ route('accounting.journal.index') }}" class="group relative bg-white dark:bg-[#161615] border border-border rounded-3xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i data-lucide="book-open" class="w-32 h-32 -mr-8 -mt-8 rotate-12"></i>
                </div>
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-primary/10 text-primary rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i data-lucide="book-open" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Journal</h3>
                    <p class="text-sm text-gray-500 leading-relaxed mb-6">Saisie et historique complet des écritures comptables.</p>
                    <div class="flex items-center text-primary font-bold text-sm gap-2 uppercase tracking-widest">
                        Accéder <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('accounting.ledger') }}" class="group relative bg-white dark:bg-[#161615] border border-border rounded-3xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i data-lucide="bar-chart-2" class="w-32 h-32 -mr-8 -mt-8 rotate-12"></i>
                </div>
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-indigo-500/10 text-indigo-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i data-lucide="bar-chart-2" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Grand Livre</h3>
                    <p class="text-sm text-gray-500 leading-relaxed mb-6">Consultation par compte avec soldes progressifs.</p>
                    <div class="flex items-center text-indigo-600 font-bold text-sm gap-2 uppercase tracking-widest">
                        Accéder <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('accounting.balance') }}" class="group relative bg-white dark:bg-[#161615] border border-border rounded-3xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i data-lucide="scale" class="w-32 h-32 -mr-8 -mt-8 rotate-12"></i>
                </div>
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-green-500/10 text-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i data-lucide="scale" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Balance</h3>
                    <p class="text-sm text-gray-500 leading-relaxed mb-6">Vérification de l'équilibre comptable par classe.</p>
                    <div class="flex items-center text-green-600 font-bold text-sm gap-2 uppercase tracking-widest">
                        Accéder <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('accounting.bilan') }}" class="group relative bg-white dark:bg-[#161615] border border-border rounded-3xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-blue-500/10 text-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i data-lucide="briefcase" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Bilan</h3>
                    <p class="text-sm text-gray-500 leading-relaxed mb-6">Actif, Passif et situation patrimoniale de l'entreprise.</p>
                    <div class="flex items-center text-blue-600 font-bold text-sm gap-2 uppercase tracking-widest">
                        Accéder <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('accounting.resultat') }}" class="group relative bg-white dark:bg-[#161615] border border-border rounded-3xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-emerald-500/10 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i data-lucide="trending-up" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Résultat</h3>
                    <p class="text-sm text-gray-500 leading-relaxed mb-6">Compte de résultat, charges et produits de la période.</p>
                    <div class="flex items-center text-emerald-600 font-bold text-sm gap-2 uppercase tracking-widest">
                        Accéder <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('accounting.help') }}" class="group relative bg-white dark:bg-[#161615] border border-border rounded-3xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-gray-500/10 text-gray-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i data-lucide="help-circle" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Guide & Aide</h3>
                    <p class="text-sm text-gray-500 leading-relaxed mb-6">Documentation et assistance pour utiliser Comptafriq.</p>
                    <div class="flex items-center text-gray-500 font-bold text-sm gap-2 uppercase tracking-widest">
                        Consulter <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </div>
            </a>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const API_BASE = '{{ url('/api') }}';
const USER_KEY = 'comptafriq_user';

function getUser() {
    try { return JSON.parse(localStorage.getItem(USER_KEY)) || null; }
    catch { return null; }
}

function getRoleLabel(role) {
    const roles = { admin: '🔑 Administrateur', comptable: '📊 Comptable', utilisateur: '👤 Utilisateur' };
    return roles[role] || '👤 Utilisateur';
}

// Initialiser le dashboard
async function initDashboard() {
    const user = getUser();

    if (user) {
        document.getElementById('dash-username').textContent = user.name || 'Utilisateur';
        document.getElementById('dash-role-text').textContent = getRoleLabel(user.role || 'utilisateur');

        if (user.entreprise_id) {
            // Charger les infos entreprise
            try {
                const res  = await fetch(`${API_BASE}/entreprise/info?user_id=${user.id}`);
                const data = await res.json();
                if (data.success) {
                    document.getElementById('dash-entreprise-badge').classList.remove('hidden');
                    document.getElementById('dash-entreprise-name').textContent = data.entreprise.name;
                    document.getElementById('dash-entreprise-code').textContent = 'ID: ' + data.entreprise.code;
                }
            } catch {}
        } else {
            // Afficher le bandeau d'alerte
            document.getElementById('no-entreprise-banner').classList.remove('hidden');
        }
    }
}

// Lier une entreprise depuis le dashboard
async function linkEntreprise() {
    const code = document.getElementById('quick-code').value.trim().toUpperCase();
    const user  = getUser();
    const msg   = document.getElementById('link-msg');
    const btn   = document.getElementById('link-btn');

    if (!code) {
        showLinkMsg('Saisissez un code entreprise.', 'amber');
        return;
    }

    if (!user) {
        showLinkMsg('Session expirée. Veuillez vous reconnecter.', 'red');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 animate-spin"></i>';

    try {
        const res  = await fetch(`${API_BASE}/entreprise/join`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ code: code, user_id: user.id })
        });

        const data = await res.json();

        if (data.success) {
            user.entreprise_id = data.entreprise.id;
            user.role = 'comptable';
            localStorage.setItem(USER_KEY, JSON.stringify(user));

            showLinkMsg('✅ ' + data.message, 'green');
            setTimeout(() => window.location.reload(), 1800);
        } else {
            showLinkMsg('❌ ' + (data.message || 'Code introuvable.'), 'red');
        }
    } catch {
        showLinkMsg('Erreur de connexion au serveur.', 'red');
    } finally {
        btn.disabled = false;
        lucide.createIcons();
        btn.innerHTML = '<i data-lucide="link" class="w-4 h-4"></i> Lier';
        lucide.createIcons();
    }
}

function showLinkMsg(text, color) {
    const el = document.getElementById('link-msg');
    const colors = {
        green:  'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
        red:    'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
        amber:  'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
    };
    el.className = colors[color] + ' mt-3 text-sm font-medium rounded-lg px-4 py-2';
    el.textContent = text;
    el.classList.remove('hidden');
}

// Soumettre avec Entrée dans le champ code
document.getElementById('quick-code')?.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') linkEntreprise();
});

initDashboard();
</script>
@endsection
