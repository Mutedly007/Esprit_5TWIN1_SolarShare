<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 — Accès Réservé | SolarShare</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 36 36'><circle cx='18' cy='18' r='7' fill='%23FFB020'/></svg>">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Rubik:wght@500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --ss-amber: #FFB020;
            --ss-orange: #FF6B3D;
            --ss-teal: #0F5C6B;
            --ss-dark: #071520;
            --ss-card-bg: rgba(16, 32, 48, 0.75);
            --ss-border: rgba(255, 255, 255, 0.08);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at 50% 20%, #102d42 0%, #081724 50%, #040c14 100%);
            color: #E2E8F0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Subtle glowing background orbs */
        .glow-orb-1 {
            position: absolute;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(255, 176, 32, 0.12) 0%, rgba(255, 107, 61, 0) 70%);
            top: 5%;
            left: 50%;
            transform: translateX(-50%);
            pointer-events: none;
            filter: blur(40px);
        }

        .glow-orb-2 {
            position: absolute;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(15, 92, 107, 0.25) 0%, rgba(15, 92, 107, 0) 70%);
            bottom: 10%;
            left: 50%;
            transform: translateX(-50%);
            pointer-events: none;
            filter: blur(50px);
        }

        .error-card {
            background: var(--ss-card-bg);
            border: 1px solid var(--ss-border);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            max-width: 520px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.6), 0 0 40px -10px rgba(255, 176, 32, 0.08);
            position: relative;
            z-index: 2;
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .brand-link {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
            color: #ffffff;
            font-size: 1.15rem;
            font-weight: 800;
            margin-bottom: 2rem;
            letter-spacing: -0.02em;
        }

        .brand-link span {
            color: var(--ss-amber);
        }

        .icon-wrapper {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem auto;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(255, 176, 32, 0.15), rgba(255, 107, 61, 0.08));
            border: 1px solid rgba(255, 176, 32, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 30px rgba(255, 176, 32, 0.18);
        }

        .error-code-badge {
            display: inline-block;
            background: rgba(255, 107, 61, 0.15);
            border: 1px solid rgba(255, 107, 61, 0.35);
            color: #FF8F6B;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            padding: 0.35rem 0.85rem;
            border-radius: 30px;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .error-title {
            font-size: 1.65rem;
            font-weight: 800;
            color: #FFFFFF;
            letter-spacing: -0.02em;
            margin-bottom: 0.85rem;
            line-height: 1.25;
        }

        .error-desc {
            font-size: 0.95rem;
            color: #94A3B8;
            line-height: 1.6;
            margin-bottom: 1.75rem;
        }

        .user-info-box {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 14px;
            padding: 0.85rem 1rem;
            font-size: 0.85rem;
            color: #94A3B8;
            margin-bottom: 2rem;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-info-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--ss-amber), var(--ss-orange));
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        .actions-group {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .btn-primary-solar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, var(--ss-amber), var(--ss-orange));
            color: #071520;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 0.85rem 1.5rem;
            border-radius: 12px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 20px rgba(255, 176, 32, 0.3);
            width: 100%;
        }

        .btn-primary-solar:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 25px rgba(255, 176, 32, 0.45);
        }

        .btn-secondary-solar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.06);
            color: #CBD5E1;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.75rem 1.25rem;
            border-radius: 12px;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.1);
            cursor: pointer;
            transition: all 0.2s ease;
            width: 100%;
        }

        .btn-secondary-solar:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #FFFFFF;
        }

        .error-footer {
            margin-top: 2rem;
            font-size: 0.78rem;
            color: #64748B;
        }
    </style>
</head>
<body>

    <div class="glow-orb-1"></div>
    <div class="glow-orb-2"></div>

    <div class="error-card">
        <!-- Brand -->
        <a href="{{ url('/') }}" class="brand-link">
            <svg width="28" height="28" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="18" cy="18" r="7" fill="#FFB020" />
                <path d="M18 2V7M18 29V34M2 18H7M29 18H34M6.68 6.68L10.22 10.22M25.78 25.78L29.32 29.32M6.68 29.32L10.22 25.78M25.78 10.22L29.32 6.68" stroke="#FF6B3D" stroke-width="2.6" stroke-linecap="round" />
            </svg>
            Solar<span>Share</span>
        </a>

        <!-- Glowing Lock Icon -->
        <div class="icon-wrapper">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#FFB020" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                <circle cx="12" cy="16" r="1.5" fill="#FFB020"></circle>
            </svg>
        </div>

        <!-- Error Badge & Title -->
        <div class="error-code-badge">Erreur 403 • Accès Refusé</div>
        <h1 class="error-title">Espace Réservé aux Administrateurs</h1>
        <p class="error-desc">
            {{ $exception->getMessage() ?: "Votre compte utilisateur n'a pas les privilèges administrateur requis pour accéder au tableau de bord de gestion SolarShare." }}
        </p>

        @auth
            <!-- Current User Badge -->
            <div class="user-info-box">
                <div class="user-info-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-weight: 700; color: #FFFFFF; font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ auth()->user()->name }}
                    </div>
                    <div style="font-size: 0.78rem; color: #94A3B8; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ auth()->user()->email }} · Rôle : <span style="color: #FFB020; font-weight: 600;">{{ auth()->user()->role ?? 'Utilisateur' }}</span>
                    </div>
                </div>
            </div>
        @endauth

        <!-- Action Buttons -->
        <div class="actions-group">
            <a href="{{ url('/') }}" class="btn-primary-solar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                Retour à l'accueil SolarShare
            </a>

            @auth
                <form method="POST" action="{{ route('logout') }}" style="margin: 0; width: 100%;">
                    @csrf
                    <button type="submit" class="btn-secondary-solar">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        Se déconnecter / Changer de compte
                    </button>
                </form>
            @endauth
        </div>

        <div class="error-footer">
            &copy; {{ date('Y') }} SolarShare — Énergie propre partagée
        </div>
    </div>

</body>
</html>
