<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Panel Wolontariuszy</title>
    <style>
        /* RESET I PODSTAWOWE STYLE */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f4f6f9; color: #333; }

        /* NAWIGACJA                        */
        .navbar {
            display: flex;                  /* Układa elementy (linki) obok siebie w rzędzie */
            justify-content: space-between; /* Spycha linki na lewo, a przycisk logowania na prawo */
            align-items: center;            /* Wyrównuje tekst idealnie w pionie */
            background-color: white;        /* Białe tło paska */
            border: 3px solid black;        /* Gruba czarna ramka z makiety kolegi */
            padding: 10px 20px;             /* Wewnętrzne odstępy (góra/dół, lewo/prawo) */
            width: 100%;                    /* Rozciągnięcie na całą szerokość ekranu */
        }

        .nav-links {
            display: flex;                  /* Linki wewnątrz też układamy obok siebie */
            gap: 40px;                      /* Odstępy między poszczególnymi napisami */
            list-style: none;               /* Usuwa domyślne kropki z listy ul */
        }

        .nav-links a {
            text-decoration: none;          /* Usuwa domyślne podkreślenie linków */
            color: black;                   /* Czarny kolor tekstu */
            font-size: 18px;                /* Rozmiar czcionki */
        }

        .nav-auth {
            border: 3px solid black;        /* Ramka wokół przycisku "Profil/Zaloguj" */
            padding: 5px 15px;              /* Odstępy wewnątrz ramki logowania */
            font-weight: bold;
            text-decoration: none;
            color: black;
        }

        /* GŁÓWNA TREŚĆ STRONY */
        .main-content {
            max-width: 600px;               /* Ogranicza szerokość formularza i listy */
            margin: 30px auto;              /* Centruje cały blok na środku ekranu (auto) */
            padding: 0 20px;
        }

        .container { 
            background: white; 
            padding: 20px;                  /* Odstęp między krawędzią a zawartością */
            border-radius: 8px;             /* Zaokrąglone rogi dla estetyki */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
            margin-bottom: 20px;            /* Odpychanie elepentów znajdujących się pod spodem */
        }

        h2 { border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 15px; }

        .form-group { margin-bottom: 15px; }

        label { display: block; margin-bottom: 5px; font-weight: bold; }

        input[type="text"], input[type="password"] { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        
        button { background: #007bff; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; width: 100%; }

        button:hover { background: #0056b3; }
        
        .alert { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        
        .alert-danger { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

    <nav class="navbar">                                                                        /* Menu główne */
        <ul class="nav-links">                                                                  /* ul - lista nieuporządkowana (punktowa) /*
            <li><a href="#">spacery</a></li>                                                    /* li - element listy, a - link */
            <li><a href="{{ route('volunteers.index') }}">zarządzanie wolontariuszami</a></li>
            <li><a href="#">zarządzanie pracownikami</a></li>
            <li><a href="#">zarządzanie psami</a></li>
        </ul>
        
        <a href="#" class="nav-auth">Profil/Zaloguj</a>
    </nav>

    <main class="main-content">                                                                 /* Główna zawartość strony */

        @if(session('success'))                                                                 /* Sprawdza, czy w sesji jest ustawiona wiadomość sukcesu */
            <div class="alert">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())                                                                     /* Sprawdza, czy są jakieś błędy walidacji */
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="container">                                                                 /* Sekcja z listą wolontariuszy */
            <h2>Zarejestrowani Wolontariusze</h2>
            @if($volunteers->isEmpty())
                <p>Brak wolontariuszy w bazie. Dodaj pierwszego poniżej!</p>                    /*akapit (paragraf) z informacją, gdy nie ma żadnych wolontariuszy */
            @else
                <ul style="padding-left: 20px;">
                    @foreach($volunteers as $volunteer)
                        <li style="margin-bottom: 5px;">
                            <strong>{{ $volunteer->account->Name }} {{ $volunteer->account->Last_Name }}</strong>    /*pogrubianie tekstu z imieniem i nazwiskiem wolontariusza */
                            (Login: {{ $volunteer->account->Login }}) - 
                            Doświadczenie: {{ $volunteer->Is_Experienced ? 'Tak' : 'Nie' }}
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="container">                                                                 /* Sekcja z formularzem dodawania nowego wolontariusza */
            <h2>Dodaj nowego wolontariusza</h2>
            <form action="{{ route('volunteers.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="Name">Imię:</label>
                    <input type="text" id="Name" name="Name" required value="{{ old('Name') }}">
                </div>

                <div class="form-group">
                    <label for="Last_Name">Nazwisko:</label>
                    <input type="text" id="Last_Name" name="Last_Name" required value="{{ old('Last_Name') }}">
                </div>

                <div class="form-group">
                    <label for="Login">Login:</label>
                    <input type="text" id="Login" name="Login" required value="{{ old('Login') }}">
                </div>

                <div class="form-group">
                    <label for="Password">Hasło:</label>
                    <input type="password" id="Password" name="Password" required>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="Is_Experienced" value="1" {{ old('Is_Experienced') ? 'checked' : '' }}>
                        Czy posiada doświadczenie?
                    </label>
                </div>

                <button type="submit">Zarejestruj wolontariusza</button>
            </form>
        </div>

    </main>

</body>
</html>