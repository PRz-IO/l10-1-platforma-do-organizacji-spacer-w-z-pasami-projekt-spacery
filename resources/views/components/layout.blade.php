<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>{{ isset($title) ? $title : 'Test'}}</title>
    <link rel="stylesheet" href="{{ asset('css/default.css') }}">
    
    {{-- Nowoczesne style dla odświeżonego Navbaru --}}
    <style>
        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #fdfdfc;
        }

        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            padding: 0 30px;
            height: 65px;
            border-bottom: 1px solid #e3e3e0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-brand {
            font-size: 18px;
            font-weight: bold;
            color: #1b1b18;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .nav-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-links {
            display: flex;
            align-items: center;
        }

        .nav-links a {
            color: #706f6c;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            padding: 8px 14px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        /* Efekt hover dla aktywnych linków */
        .nav-links a:hover:not(.nav-dummy) {
            background-color: #f4f4f2;
            color: #1b1b18;
        }

        /* Stylowanie zablokowanych opcji (Twoje nav-dummy) */
        .nav-links a.nav-dummy {
            color: #c1c0bc !important;
            cursor: not-allowed;
            background: transparent !important;
        }

        /* Akcenty dla przycisków uwierzytelniania */
        .nav-links a.login-btn {
            color: #007bff;
            border: 1px solid #e3e3e0;
            margin-right: 4px;
        }
        .nav-links a.login-btn:hover {
            background-color: #f0f7ff;
            border-color: #007bff;
        }

        .nav-links a.signup-btn {
            background: #007bff;
            color: white !important;
        }
        .nav-links a.signup-btn:hover {
            background: #0056b3;
        }

        .nav-links a.logout-btn {
            color: #dc3545;
        }
        .nav-links a.logout-btn:hover {
            background-color: #fff5f5;
        }

        .main-content {
            min-height: calc(100vh - 65px);
        }
    </style>
    @stack('styles')
</head>
<body>

    <nav class="navbar"> 
        
        {{-- Lewa strona: Nazwa aplikacji / Logo --}}
        <a href="/" class="nav-brand">
            Bark & Boop 🐾
        </a>

        {{-- Środek: Główne linki nawigacyjne --}}
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
                    href="#" class="nav-dummy" title="Dostępne tylko dla zweryfikowanych pracowników"
                @endif
                >Zarządzanie Wolontariuszami</a>
            </div>
            
            <div class="nav-links">
                <a 
                @if (\App\Utilities\CurrUser::IsLogged() && 
                \App\Utilities\CurrUser::getRole()=="Worker" &&
                \App\Utilities\CurrUser::getAcc_State() =="Active")
                     href="{{ route('workers.index') }}"    
                @else
                    href="#" class="nav-dummy" title="Dostępne tylko dla aktywnych pracowników"
                @endif
                >Zarządzanie Pracownikami</a>
            </div>
            
            <div class="nav-links">
                <a href="{{ route('dogs.index') }}">Psy</a>
            </div>
        </div>

        {{-- Prawa strona: Profil i Logowanie --}}
        <div class="nav-group">
            @if (\App\Utilities\CurrUser::IsLogged())
                <div class="nav-links">    
                    <a href="{{ route('profile') }}">Profil</a>
                </div>
                <div class="nav-links">    
                    <a href="{{ route('login.logout') }}" class="logout-btn">Wyloguj</a>
                </div>
            @else
                <div class="nav-links">    
                    <a href="{{ route('login.login') }}" class="login-btn">Zaloguj</a>
                </div>
                <div class="nav-links">    
                    <a href="{{ route('signup.index') }}" class="signup-btn">Zarejestruj</a>
                </div>
            @endif
        </div>

    </nav>

    {{-- Główna treść strony --}}
    <main class="main-content">                                                                 
        {{ $slot }}
    </main>

</body>
</html>