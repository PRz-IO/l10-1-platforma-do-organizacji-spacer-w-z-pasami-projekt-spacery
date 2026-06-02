<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>{{ isset($title) ? $title : 'Test'}}</title>
    <link rel="stylesheet" href="{{ asset('css/default.css') }}">
    @stack('styles')
</head>
<body>

    <nav class="navbar">                                                                        
        <div class="nav-links">
            <a href="#">Spacery</a>
        </div>
        <div class="nav-links">
            @if (\App\Utilities\CurrUser::IsLogged() && \App\Utilities\CurrUser::getRole()=="Worker")
                <a href="{{ route('worker.volunteers.index') }}">Zarządzanie Wolontariuszami</a>    
            @else
                <a href="#" class="nav-dummy">Zarządzanie Wolontariuszami</a>
            @endif
            
        </div>
        <div class="nav-links">
            <a href="#">Zarządzanie Pracownikami</a>
        </div>
        <div class="nav-links">
            <a href="#">Zarządzanie Psami</a>
        </div>
        <div class="nav-links">

            @if (\App\Utilities\CurrUser::IsLogged())
            <div class="nav-links">    
            <a href="#">Profil</a>
            </div>
            <div class="nav-links">    
            <a href="{{ route('login.logout') }}">Wyloguj</a>
            </div>
            @else
            <div class="nav-links">    
            <a href="{{ route('login.login') }}">Zaloguj</a>
            </div>
            <div class="nav-links">    
            <a href="#">Zarejestruj</a>
            </div>
            @endif
            
        </div>
    </nav>

    <main class="main-content">                                                                 

        {{ $slot }}

    </main>

</body>
</html>