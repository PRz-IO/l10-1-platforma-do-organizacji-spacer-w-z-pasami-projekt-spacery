<x-layout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/signup.css') }}">
    @endpush
    <div class="container" style="padding: 0px; height: 90vh;">
        <div class="haikyuu">
            <div style="display: flex; align-items: center; text-align: center;">
            <h2 style=" margin: 0px; padding: 0px;">Rejestracja</h2><br><br>
            </div>
            
            <form class="haikyuu1" action="{{ route('signup.signup') }}" method="post">
                @csrf
                <div>
                    <label for="login">Login:</label>
                    <input type="text" class="LogInp" id="login" name="login" @isset($L) value="{{ $L }}" @endisset>
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input type="text" class="LogInp" id="email" name="email" @isset($E) value="{{ $E }}" @endisset>
                </div>
                <div>
                    <label for="password">Hasło:</label>
                    <input type="password" class="LogInp" id="password" name="password"><br><br>
                </div>
                <div>
                    <label for="rpassword">Powtórz Hasło:</label>
                    <input type="password" class="LogInp" id="rpassword" name="rpassword"><br><br>
                </div>
                <div>
                    <label for="name">Imię:</label>
                    <input type="text" class="LogInp" id="name" name="name" @isset($N) value="{{ $N }}" @endisset>
                </div>
                <div>
                    <label for="surname">Nazwisko:</label>
                    <input type="text" class="LogInp" id="surname" name="surname" @isset($S) value="{{ $S }}" @endisset>
                </div>
                <div style="grid-column: span 2;">
                    <label for="phone">Numer Telefonu:</label>
                    <input type="text" class="LogInp" id="phone" name="phone" @isset($P) value="{{ $P }}" @endisset>
                </div>
                <div>
                    <label for="role1">Wolontariusz</label>
                    <input type="radio" class="LogInp" id="role1" name="role" value="Volunteer" @isset($R)
                        @if ($R=="Volunteer")
                            checked
                        @endif
                    @else
                    checked
                    @endisset>
                </div>
                <div>
                    <label for="role2">Pracownik</label>
                    <input type="radio" class="LogInp" id="role2" name="role" value="Worker"@isset($R)
                        @if ($R=="Worker")
                            checked
                        @endif
                    @endisset>
                </div>
                <div><button type="submit" class="LogBtn">Zarejestruj</button></div>
                <div><a href="{{ route('login.login') }}">
                    <button type="button" class="LogBtn">Logowanie</button>
                </a></div>
            </form>
            
            <div style="display: flex;">
                <br><br><br>
                    @isset($Err)
                        <h5>
                            {{ $Err }}
                        </h5>
                    @endisset
                <br><br><br>
            </div>
        </div>
    </div>
</x-layout>