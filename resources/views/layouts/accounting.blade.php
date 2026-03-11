<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comptabilité - @yield('title')</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Tom Select -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: '#003366',
                        'primary-light': '#0055aa',
                        accent: '#f53003',
                        bg: '#FDFDFC',
                        'card-bg': '#FFFFFF',
                        border: '#e3e3e0',
                    }
                }
            }
        }
    </script>
    
    <style>
        body { font-family: 'Outfit', sans-serif; overflow-x: hidden; }
        .sidebar-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        
        /* Table Responsive Wrapper */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 0.75rem;
        }

        /* Sidebar Collapsed State (Initial) */
        .sidebar-collapsed {
            width: 80px !important;
        }
        .sidebar-collapsed .sidebar-label,
        .sidebar-collapsed .sidebar-header-text {
            display: none !important;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -260px;
                height: 100vh;
                z-index: 50;
            }
            .sidebar.mobile-open {
                left: 0;
            }
            .main-content {
                padding: 1.5rem !important;
            }
        }
    </style>
    <script>
        // Appliquer l'état du sidebar IMMÉDIATEMENT pour éviter le flash
        (function() {
            const collapsed = localStorage.getItem('sidebar-collapsed') === 'true';
            if (collapsed && window.innerWidth > 768) {
                document.documentElement.classList.add('sidebar-is-collapsed');
            }
        })();
    </script>
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] min-h-screen">
    <!-- Overlay for mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden"></div>

    <div class="flex min-h-screen relative">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar sidebar-transition w-[260px] md:sticky md:top-0 md:h-screen bg-white dark:bg-[#161615] border-r border-border flex flex-shrink-0 flex-col py-3 px-4 shadow-sm z-50 overflow-y-auto">
            <script>
                // Appliquer la classe si nécessaire avant que l'élément soit affiché
                if (localStorage.getItem('sidebar-collapsed') === 'true' && window.innerWidth > 768) {
                    document.getElementById('sidebar').classList.add('sidebar-collapsed');
                    document.getElementById('sidebar').classList.replace('w-[260px]', 'w-[80px]');
                }
            </script>
            <!-- Toggle Button (Floating) -->
            <button id="toggle-sidebar" class="fixed left-[244px] top-10 sidebar-transition bg-accent text-white border-[3px] border-[#FDFDFC] dark:border-[#0a0a0a] w-8 h-8 rounded-full flex items-center justify-center shadow-lg hover:scale-110 hover:bg-primary transition-all z-[60] hidden md:flex">
                <script>
                    if (localStorage.getItem('sidebar-collapsed') === 'true' && window.innerWidth > 768) {
                        document.currentScript.parentElement.style.left = '64px';
                    }
                </script>
                <i id="toggle-chevron" data-lucide="chevron-left"></i>
            </button>

            <!-- Mobile Close Button -->
            <button id="close-mobile-sidebar" class="md:hidden absolute top-4 right-4 text-text-muted">
                <i data-lucide="x"></i>
            </button>

            <div class="flex items-center h-10 mb-6 px-2 opacity-100 sidebar-header-text overflow-hidden transition-all duration-300">
                <h2 class="text-xl font-extrabold text-accent whitespace-nowrap">Logicompta</h2>
            </div>
            
            <nav class="flex flex-col gap-1">
                <a href="/" class="flex items-center gap-4 px-4 py-3 rounded-xl font-medium transition-all {{ request()->is('/') ? 'bg-primary text-white shadow-md' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5' }}">
                    <i class="w-5 h-5" data-lucide="home"></i>
                    <span class="sidebar-label transition-all duration-300">Accueil</span>
                </a>

                <div class="sidebar-label text-[10px] uppercase font-bold text-gray-400 mt-6 px-4 mb-2 tracking-widest hidden md:block">Comptabilité Générale</div>
                <div class="md:hidden border-t border-border my-4 mx-2"></div>
                
                <a href="{{ route('accounting.journal.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-xl font-medium transition-all {{ request()->routeIs('accounting.journal.index') ? 'bg-primary text-white shadow-md' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5' }}">
                    <i class="w-5 h-5" data-lucide="book-open"></i>
                    <span class="sidebar-label transition-all duration-300">Journal</span>
                </a>
                <a href="{{ route('accounting.journal.create') }}" class="flex items-center gap-4 px-4 py-3 rounded-xl font-medium transition-all {{ request()->routeIs('accounting.journal.create') ? 'bg-primary text-white shadow-md' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5' }}">
                    <i class="w-5 h-5" data-lucide="edit"></i>
                    <span class="sidebar-label transition-all duration-300">Saisie</span>
                </a>
                <a href="{{ route('accounting.ledger') }}" class="flex items-center gap-4 px-4 py-3 rounded-xl font-medium transition-all {{ request()->routeIs('accounting.ledger') ? 'bg-primary text-white shadow-md' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5' }}">
                    <i class="w-5 h-5" data-lucide="bar-chart-2"></i>
                    <span class="sidebar-label transition-all duration-300">Grand Livre</span>
                </a>
                <a href="{{ route('accounting.balance') }}" class="flex items-center gap-4 px-4 py-3 rounded-xl font-medium transition-all {{ request()->routeIs('accounting.balance') ? 'bg-primary text-white shadow-md' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5' }}">
                    <i class="w-5 h-5" data-lucide="scale"></i>
                    <span class="sidebar-label transition-all duration-300">Balance</span>
                </a>
                <a href="{{ route('accounting.bilan') }}" class="flex items-center gap-4 px-4 py-3 rounded-xl font-medium transition-all {{ request()->routeIs('accounting.bilan') ? 'bg-primary text-white shadow-md' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5' }}">
                    <i class="w-5 h-5" data-lucide="briefcase"></i>
                    <span class="sidebar-label transition-all duration-300">Bilan</span>
                </a>
                <a href="{{ route('accounting.resultat') }}" class="flex items-center gap-4 px-4 py-3 rounded-xl font-medium transition-all {{ request()->routeIs('accounting.resultat') ? 'bg-primary text-white shadow-md' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5' }}">
                    <i class="w-5 h-5" data-lucide="trending-up"></i>
                    <span class="sidebar-label transition-all duration-300">Résultat</span>
                </a>

                <div class="sidebar-label text-[10px] uppercase font-bold text-gray-400 mt-6 px-4 mb-2 tracking-widest hidden md:block">Support</div>
                <a href="{{ route('accounting.help') }}" class="flex items-center gap-4 px-4 py-3 rounded-xl font-medium transition-all {{ request()->routeIs('accounting.help') ? 'bg-primary text-white shadow-md' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5' }}">
                    <i class="w-5 h-5" data-lucide="help-circle"></i>
                    <span class="sidebar-label transition-all duration-300">Guide & Aide</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content flex-1 min-w-0 p-6 md:p-10 transition-all">
            <!-- Mobile Top Bar -->
            <header class="md:hidden flex items-center justify-between mb-6">
                <button id="open-mobile-sidebar" class="p-2 bg-white rounded-lg border border-border shadow-sm">
                    <i data-lucide="menu"></i>
                </button>
                <h2 class="text-xl font-bold">Logicompta</h2>
                <div class="w-10"></div> <!-- Spacer -->
            </header>

            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 flex items-center gap-3">
                    <i class="w-5 h-5" data-lucide="check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggle-sidebar');
        const toggleChevron = document.getElementById('toggle-chevron');
        const openMobileBtn = document.getElementById('open-mobile-sidebar');
        const closeMobileBtn = document.getElementById('close-mobile-sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const body = document.body;

        lucide.createIcons();

        // Function to apply collapsed state
        const setSidebarState = (collapsed) => {
            if (collapsed) {
                sidebar.classList.replace('w-[260px]', 'w-[80px]');
                sidebar.classList.add('sidebar-collapsed');
                if (toggleBtn) toggleBtn.style.left = '64px';
                if (toggleChevron) toggleChevron.setAttribute('data-lucide', 'chevron-right');
            } else {
                sidebar.classList.replace('w-[80px]', 'w-[260px]');
                sidebar.classList.remove('sidebar-collapsed');
                if (toggleBtn) toggleBtn.style.left = '244px';
                if (toggleChevron) toggleChevron.setAttribute('data-lucide', 'chevron-left');
            }
            lucide.createIcons();
            localStorage.setItem('sidebar-collapsed', collapsed);
        };

        // Web Sidebar Toggle
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                const currentlyCollapsed = sidebar.classList.contains('sidebar-collapsed');
                setSidebarState(!currentlyCollapsed);
            });
        }

        // Mobile Sidebar
        const toggleMobile = () => {
            sidebar.classList.toggle('mobile-open');
            sidebarOverlay.classList.toggle('hidden');
        };

        if (openMobileBtn) openMobileBtn.addEventListener('click', toggleMobile);
        if (closeMobileBtn) closeMobileBtn.addEventListener('click', toggleMobile);
        if (sidebarOverlay) sidebarOverlay.addEventListener('click', toggleMobile);

        // Load Initial State
        const savedState = localStorage.getItem('sidebar-collapsed');
        if (savedState === 'true' && window.innerWidth > 768) {
            setSidebarState(true);
            // S'assurer que l'icône est correcte après le rendu initial
            if (toggleChevron) {
                toggleChevron.setAttribute('data-lucide', 'chevron-right');
                lucide.createIcons();
            }
        }
    </script>
    @yield('scripts')
</body>
</html>
