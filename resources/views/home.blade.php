<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comptafriq - Accueil</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #1A202C;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .background-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(255,255,255,0.1) 0%, transparent 50%);
            pointer-events: none;
        }
        
        .main-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem;
            position: relative;
            z-index: 1;
        }
        
        .content-wrapper {
            text-align: center;
            max-width: 600px;
            animation: fadeInUp 1s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    
        .header-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        /* Logo */
        .logo-container {
            margin-bottom: 0.5rem;
            text-align: center;
        }
        
        .logo {
            width: 420px;
            height: auto;
            display: block;
            margin: 0 auto;
            filter: drop-shadow(0 10px 30px rgba(70, 130, 180, 0.4));
        }
        
        /* Texte de bienvenue */
        .welcome-text {
            text-align: center;
            margin-bottom: 1.25rem;
        }
        
        .welcome-text h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            background: linear-gradient(135deg, #60A5FA 0%, #3B82F6 50%, #1D4ED8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            color: transparent;
            text-shadow: 0 0 30px rgba(59, 130, 246, 0.3);
        }
        
        .welcome-text p {
            font-size: 1.2rem;
            color: #93C5FD;
            margin-bottom: 1.25rem;
            font-weight: 400;
        }
        
        /* Boutons */
        .buttons-container {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 1rem 2.5rem;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            min-width: 150px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #1D4ED8 0%, #1E40AF 100%);
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(59, 130, 246, 0.4);
        }
        
        .btn-secondary {
            background: transparent;
            color: #93C5FD;
            border: 2px solid #93C5FD;
        }
        
        .btn-secondary:hover {
            background: #3B82F6;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(59, 130, 246, 0.2);
        }
        
        /* Footer */
        .footer {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            color: #718096;
            font-size: 0.9rem;
            text-align: center;
        }
        
        /* Particules flottantes */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(147, 197, 253, 0.4);
            border-radius: 50%;
            animation: float 15s infinite ease-in-out;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0) translateX(0);
                opacity: 0.4;
            }
            25% {
                transform: translateY(-20px) translateX(10px);
                opacity: 0.8;
            }
            50% {
                transform: translateY(-10px) translateX(-10px);
                opacity: 0.6;
            }
            75% {
                transform: translateY(-30px) translateX(5px);
                opacity: 0.9;
            }
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }
            
            .header-section {
                flex-direction: column;
                align-items: center;
                margin-bottom: 1.5rem;
            }
            
            .logo {
                width: 280px;
            }
            
            .welcome-text h1 {
                font-size: 2.5rem;
            }
            
            .welcome-text p {
                font-size: 1rem;
            }
            
            .buttons-container {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 250px;
            }
        }
    </style>
</head>
<body>
    <div class="background-pattern"></div>
    
    <!-- Particules flottantes -->
    <div class="particles" id="particles"></div>
    
    <!-- Container principal -->
    <div class="main-container">
        <div class="content-wrapper">
            <!-- Header avec logo et texte centrés -->
            <div class="header-section">
                <!-- Logo -->
                <div class="logo-container">
                    <img src="{{ asset('images/ChatGPT Image 11 mars 2026, 10_41_49.png') }}" alt="Comptafriq Logo" class="logo">
                </div>
                
                <!-- Texte de bienvenue -->
                <div class="welcome-text">
                    <h1>Bienvenue sur Comptafriq</h1>
                    <p>Le logiciel qui facilite vos comptes !</p>
                </div>
            </div>
            
            <!-- Boutons -->
            <div class="buttons-container">
                <a href="{{ url('/login') }}" class="btn btn-primary">Sign In</a>
                <a href="{{ url('/signup') }}" class="btn btn-secondary">Sign Up</a>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} Comptafriq - Système Comptable Africain</p>
        </div>
    </div>

    <script>
        // Créer des particules flottantes
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 20;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 15 + 's';
                particle.style.animationDuration = (15 + Math.random() * 10) + 's';
                particlesContainer.appendChild(particle);
            }
        }
        
        // Gérer les redirections avec paramètres
        document.addEventListener('DOMContentLoaded', function() {
            createParticles();
        });
    </script>
</body>
</html>
