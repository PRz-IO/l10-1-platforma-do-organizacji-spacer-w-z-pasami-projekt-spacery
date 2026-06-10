<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>{{ isset($title) ? $title : 'Bark & Boop'}}</title>
    <link rel="stylesheet" href="{{ asset('css/default.css') }}">
    
    <style>
        /* Reset i style globalne */
        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8f9fa;
            /* Flexbox dla body, aby stopka zawsze była na dole, nawet gdy na stronie jest mało treści */
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ffffff;
            padding: 0 2rem;
            height: 70px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid #eaeaea;
        }

        /* Styl dla nazwy schroniska w lewym rogu (logo) */
        .nav-brand a {
            text-decoration: none;
            color: #1a202c;
            font-weight: 700;
            font-size: 1.2rem;
            letter-spacing: -0.5px;
            padding: 0.4rem 0.6rem;
            border: 1px solid #e2e8f0; /* Subtelna, jasna ramka */
            border-radius: 8px; /* Zaokrąglone rogi */
            background-color: #f8f9fa; /* Delikatne tło */
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Efekt najechania myszką na logo */
        .nav-brand a:hover {
            background-color: #edf2f7; /* Lekkie przyciemnienie tła */
            border-color: #cbd5e0; /* Nieco wyraźniejsza ramka */
        }

        /* Grupowanie linków (środek i prawa strona) */
        .nav-group {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .nav-links a {
            text-decoration: none;
            color: #4a5568;
            font-weight: 500;
            font-size: 0.95rem;
            padding: 0.5rem 0.8rem;
            border-radius: 6px;
            transition: all 0.2s ease-in-out;
        }

        /* Efekt najechania myszką */
        .nav-links a:hover {
            background-color: #edf2f7;
            color: #007bff;
        }

        /* Przyciski logowania / rejestracji / wylogowania */
        .btn-login {
            color: #007bff !important;
            border: 1px solid #007bff;
        }
        .btn-login:hover {
            background-color: #f0f7ff !important;
        }
        
        .btn-register {
            background-color: #007bff;
            color: #ffffff !important;
        }
        .btn-register:hover {
            background-color: #0056b3 !important;
        }

        .btn-logout {
            color: #dc3545 !important;
        }
        .btn-logout:hover {
            background-color: #fff5f5 !important;
            color: #bd2130 !important;
        }

        .main-content {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            flex-grow: 1; /* Pozwala głównej zawartości zająć całą dostępną przestrzeń */
            width: 100%;
            box-sizing: border-box;
        }

        /* Style dla stopki */
        .site-footer {
            background-color: #ffffff;
            border-top: 1px solid #eaeaea;
            padding: 1.5rem 2rem;
            text-align: center;
            color: #706f6c;
            font-size: 0.9rem;
            margin-top: auto; /* Dopycha stopkę na sam dół ekranu */
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .footer-content p {
            margin: 0;
        }
        
        .footer-accent {
            color: #1b1b18;
            font-weight: 600;
        }
    </style>
    @stack('styles')
</head>
<body>

    <nav class="navbar">
        
        {{-- 1. LEWA STRONA: Nazwa schroniska --}}
        <div class="nav-brand">
            <a href="/">🐾 Strona główna</a>
        </div>

        {{-- 2. ŚRODEK: Główne linki nawigacyjne --}}
        <div class="nav-group">
            <div class="nav-links">
                <a href="{{ route('walks.index') }}">Spacery</a>
            </div>
            
            {{-- Pokazujemy tylko zalogowanemu Pracownikowi, który przeszedł weryfikację --}}
            @if (\App\Utilities\CurrUser::IsLogged() && 
                 \App\Utilities\CurrUser::getRole() == "Worker" &&
                 \App\Utilities\CurrUser::getAcc_State() != "Pending")
                <div class="nav-links">
                    <a href="{{ route('worker.volunteers.index') }}">Zarządzanie Wolontariuszami</a>
                </div>
            @endif
            
            {{-- Pokazujemy listę pracowników tylko zalogowanym --}}
            @if (\App\Utilities\CurrUser::IsLogged())
                <div class="nav-links">
                    <a href="{{ route('workers.index') }}">Pracownicy</a>
                </div>
            @endif
            
            <div class="nav-links">
                <a href="{{ route('dogs.index') }}">Psy</a>
            </div>
        </div>

        {{-- 3. PRAWA STRONA: Opcje konto --}}
        <div class="nav-group">
            @if (\App\Utilities\CurrUser::IsLogged())
                <div class="nav-links">    
                    <a href="{{ route('profile.index') }}">Profil</a>
                </div>
                <div class="nav-links">    
                    <a href="{{ route('login.logout') }}" class="btn-logout">Wyloguj</a>
                </div>
            @else
                <div class="nav-links">    
                    <a href="{{ route('login.login') }}" class="btn-login">Zaloguj</a>
                </div>
                <div class="nav-links">    
                    <a href="{{ route('signup.index') }}" class="btn-register">Zarejestruj</a>
                </div>
            @endif
        </div>
    </nav>

    <main class="main-content">                                                 
        {{ $slot }}
    </main>

    {{-- STOPKA --}}
    <footer class="site-footer">
        <div class="footer-content">
            <p>&copy; {{ date('Y') }} <span class="footer-accent">Bark & Boop</span>. Wszystkie prawa zastrzeżone.</p>
            <p style="font-size: 0.8rem; color: #a0aec0;">Projekt systemu organizacji spacerów ze zwierzętami ze schroniska.</p>
            
            {{-- Kryterium akceptacji: Komunikat w stopce --}}
            <div class="footer-notice">
                Ta strona służy wyłącznie do rezerwacji spacerów. Nie prowadzimy modułu adopcyjnego.
            </div>
        </div>
    </footer>

</body>
</html>