<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau mot de passe - Comptafriq</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #334155 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            position: relative;
            overflow-x: hidden;
        }
        
        .background-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(236, 72, 153, 0.1) 0%, transparent 50%);
            z-index: 1;
        }
        
        .main-container {
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            position: relative;
            padding: 3rem 2rem;
            text-align: center;
            max-width: 450px;
            width: 100%;
            z-index: 2;
        }
        
        /* Logo en haut */
        .logo-section {
            text-align: center;
            margin-bottom: 1.5rem;
            padding: 1rem 0;
        }
        
        .logo {
            width: 280px;
            height: auto;
            margin: 0 auto;
            display: block;
            filter: drop-shadow(0 10px 30px rgba(70, 130, 180, 0.4));
            transition: transform 0.3s ease;
        }
        
        .logo:hover {
            transform: scale(1.05);
        }
        
        /* Titre */
        .welcome-text {
            margin-bottom: 2rem;
        }
        
        .welcome-text h2 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: white;
        }
        
        .welcome-text p {
            color: #93C5FD;
            margin-bottom: 1rem;
            font-size: 1rem;
            line-height: 1.6;
        }
        
        .form-container {
            width: 100%;
            max-width: 350px;
            margin: 0 auto;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #93C5FD;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #374151;
            border-radius: 10px;
            font-size: 1rem;
            background: #1A202C;
            color: white;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #3B82F6;
            background: #1A202C;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .form-group input::placeholder {
            color: #9CA3AF;
        }
        
        .password-requirements {
            text-align: left;
            margin-top: 0.5rem;
            font-size: 0.8rem;
            color: #9CA3AF;
            line-height: 1.4;
        }
        
        .password-requirements li {
            margin-bottom: 0.2rem;
        }
        
        .btn-primary {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #1D4ED8 0%, #1E40AF 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
        }
        
        .btn-secondary {
            width: 100%;
            padding: 0.75rem;
            background: transparent;
            color: #93C5FD;
            border: 2px solid #93C5FD;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: #3B82F6;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.2);
        }
        
        .message {
            padding: 0.75rem;
            margin: 1rem 0;
            border-radius: 8px;
            display: none;
            font-size: 0.9rem;
        }
        
        .message.success {
            background: rgba(34, 197, 94, 0.1);
            color: #22C55E;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }
        
        .message.error {
            background: rgba(239, 68, 68, 0.1);
            color: #EF4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }
            
            .main-container {
                max-width: 400px;
                padding: 2rem 1.5rem;
            }
            
            .logo-section {
                margin-bottom: 1rem;
                padding: 0.5rem 0;
            }
            
            .logo {
                width: 240px;
            }
            
            .welcome-text h2 {
                font-size: 1.5rem;
            }
        }
        
        /* Animations */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="background-pattern"></div>
    
    <!-- Container principal -->
    <div class="main-container fade-in">
        <!-- Logo en haut -->
        <div class="logo-section">
            <img src="{{ asset('images/ChatGPT Image 11 mars 2026, 10_41_49.png') }}" alt="Comptafriq Logo" class="logo">
        </div>
        
        <!-- Formulaire de nouveau mot de passe -->
        <div class="form-container">
            <div class="welcome-text">
                <h2>Réinitialisation du mot de passe</h2>
                <p>Définissez votre nouveau mot de passe. Vos autres informations resteront inchangées.</p>
            </div>
            
            <form id="resetPasswordForm">
                <input type="hidden" id="token" name="token" value="">
                <input type="hidden" id="email" name="email" value="">
                
                <div class="form-group">
                    <label for="password">Nouveau mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                    <div class="password-requirements">
                        <ul>
                            <li>✓ Au moins 8 caractères</li>
                            <li>✓ Au moins 1 majuscule</li>
                            <li>✓ Au moins 1 chiffre</li>
                        </ul>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation">Confirmer le mot de passe</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                </div>
                
                <button type="submit" class="btn-primary">Définir le nouveau mot de passe</button>
            </form>
            
            <button class="btn-secondary" onclick="window.location.href='{{ url('/login') }}'">
                <i class="fas fa-arrow-left mr-2"></i>Retour à la connexion
            </button>
            
            <div id="message" class="message"></div>
        </div>
    </div>

    <script>
        const API_BASE = '{{ url('/api') }}';
        
        // Récupérer les paramètres de l'URL
        function getUrlParams() {
            const params = new URLSearchParams(window.location.search);
            return {
                token: params.get('token'),
                email: params.get('email')
            };
        }
        
        // Fonctions d'affichage
        function showMessage(message, type = 'success') {
            const messageEl = document.getElementById('message');
            messageEl.textContent = message;
            messageEl.className = `message ${type}`;
            messageEl.style.display = 'block';
            
            setTimeout(() => {
                hideMessage();
            }, 5000);
        }

        function hideMessage() {
            document.getElementById('message').style.display = 'none';
        }

        // Validation du mot de passe
        function validatePassword(password) {
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                number: /[0-9]/.test(password)
            };
            
            return {
                isValid: requirements.length && requirements.uppercase && requirements.number,
                requirements: requirements
            };
        }

        // Réinitialisation du mot de passe
        async function resetPassword(event) {
            event.preventDefault();
            
            const token = document.getElementById('token').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;

            if (!token || !email) {
                showMessage('Lien de réinitialisation invalide', 'error');
                return;
            }

            if (password !== passwordConfirmation) {
                showMessage('Les mots de passe ne correspondent pas', 'error');
                return;
            }

            const validation = validatePassword(password);
            if (!validation.isValid) {
                showMessage('Le mot de passe ne respecte pas les exigences de sécurité', 'error');
                return;
            }

            const btn = event.target.querySelector('button[type="submit"]');
            btn.textContent = 'Enregistrement en cours...';
            btn.disabled = true;

            try {
                const response = await fetch(`${API_BASE}/reset-password`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        token,
                        email,
                        password,
                        password_confirmation
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showMessage('✅ Mot de passe réinitialisé avec succès! Redirection vers la connexion...', 'success');
                    
                    setTimeout(() => {
                        window.location.href = '{{ url('/login') }}';
                    }, 3000);
                } else {
                    showMessage(data.message || 'Erreur lors de la réinitialisation du mot de passe', 'error');
                }
            } catch (error) {
                console.error('Erreur:', error);
                showMessage('Erreur de connexion au serveur', 'error');
            } finally {
                btn.textContent = 'Définir le nouveau mot de passe';
                btn.disabled = false;
            }
        }

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            const params = getUrlParams();
            
            if (!params.token || !params.email) {
                showMessage('Lien de réinitialisation invalide ou expiré', 'error');
                return;
            }

            document.getElementById('token').value = params.token;
            document.getElementById('email').value = params.email;

            // Ajouter l'écouteur d'événement au formulaire
            document.getElementById('resetPasswordForm').addEventListener('submit', resetPassword);
        });
    </script>
</body>
</html>
