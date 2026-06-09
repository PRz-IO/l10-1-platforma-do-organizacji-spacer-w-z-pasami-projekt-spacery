<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>{{ isset($title) ? $title : 'Test'}}</title>
    <link rel="stylesheet" href="{{ asset('css/default.css') }}">
    
    <style>
        /* Reset i style globalne */
        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8f9fa;
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

        /* Styl dla nazwy schroniska w lewym rogu */
        .nav-brand a {
            text-decoration: none;
            color: #1a202c;
            font-weight: 700;
            font-size: 1.2rem;
            letter-spacing: -0.5px;
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
        .nav-links a:hover:not(.nav-dummy) {
            background-color: #edf2f7;
            color: #3182ce;
        }

        /* Styl dla zablokowanych opcji */
        .nav-links a.nav-dummy {
            color: #cbd5e0;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Przyciski logowania / rejestracji / wylogowania */
        .btn-login {
            color: #3182ce !important;
            border: 1px solid #3182ce;
        }
        .btn-login:hover {
            background-color: #ebf8ff !important;
        }
        
        .btn-register {
            background-color: #3182ce;
            color: #ffffff !important;
        }
        .btn-register:hover {
            background-color: #2b6cb0 !important;
        }

        .btn-logout {
            color: #e53e3e !important;
        }
        .btn-logout:hover {
            background-color: #fff5f5 !important;
            color: #c53030 !important;
        }

        .main-content {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
    </style>
    @stack('styles')
</head>
<body>

    <nav class="navbar">
        <div style="background: #fff3cd; padding: 10px; font-size: 12px; color: #856404; text-align: center;">
            Zalogowany: {{ \App\Utilities\CurrUser::IsLogged() ? 'TAK' : 'NIE' }} | 
            Rola: "{{ \App\Utilities\CurrUser::getRole() }}" | 
            Status: "{{ \App\Utilities\CurrUser::getAcc_State() }}"
        </div>
        {{-- 1. LEWA STRONA: Nazwa schroniska (Zawsze widoczna, bezpieczna) --}}
        <div class="nav-brand">
            <a href="/">Bark & Boop 🐾</a>
        </div>

        {{-- 2. ŚRODEK: Główne linki nawigacyjne --}}
        <div class="nav-group">
            <div class="nav-links">
                <a href="{{ route('walks.index') }}">Spacery</a>
            </div>
            
            <div class="nav-links">
                <a
                @if (\App\Utilities\CurrUser::IsLogged() && 
                \App\Utilities\CurrUser::getRole()=="Worker" &&
                \App\Utilities\CurrUser::getAcc_State() !="Pending")
                     href="{{ route('worker.volunteers.index') }}"    
                @else
                    href="#" class="nav-dummy"
                @endif
                >Zarządzanie Wolontariuszami</a>
            </div>
            
            <div class="nav-links">
                <a 
                @if (\App\Utilities\CurrUser::IsLogged())
                     href="{{ route('workers.index') }}"    
                @else
                    href="#" class="nav-dummy"
                @endif
                >Pracownicy</a>
            </div>
            
            <div class="nav-links">
                <a href="{{ route('dogs.index') }}" >Psy</a>
            </div>
        </div>

        {{-- 3. PRAWA STRONA: Opcje konta --}}
        <div class="nav-group">
            @if (\App\Utilities\CurrUser::IsLogged())
                <div class="nav-links">    
                    <a href="{{ route('profile') }}">Profil</a>
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

</body>
</html>