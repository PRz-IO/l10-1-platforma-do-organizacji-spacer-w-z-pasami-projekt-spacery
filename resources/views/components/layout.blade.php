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
            @if (\App\Utilities\CurrUser::IsLogged() && 
            \App\Utilities\CurrUser::getRole()=="Worker" &&
            \App\Utilities\CurrUser::getAcc_State() !="Pending" &&
            \App\Utilities\CurrUser::getParam() ==True)
                 href="{{ '#' }}"    
            @else
                href="#" class="nav-dummy"
            @endif
            >Zarządzanie Pracownikami</a>
        </div>
        <div class="nav-links">
            <a href="{{ route('dogs.index') }}" >Psy</a>
        </div>
       

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
        <a href="{{ route('signup.index') }}">Zarejestruj</a>
        </div>
        @endif

    </nav>

    <main class="main-content">                                                                 

        {{ $slot }}

    </main>

</body>
</html>