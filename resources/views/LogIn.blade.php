<x-layout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    @endpush
    <div class="container">
        <h3>Logowanie</h3><br><br>
        <form action="{{ route('login.login') }}" method="post">
            @csrf
            <label for="login">Login:</label>
            <input type="text" class="LogInp" id="login" name="login" @isset($L)
                value="{{ $L }}"
            @endisset><br><br>
            <label for="password">Hasło:</label>
            <input type="password" class="LogInp" id="password" name="password"><br><br>
            <button type="submit" class="LogBtn">Zaloguj</button>
        </form>
        <br>
        <form action="{{ route('signup.index') }}">
        <button type="submit" class="LogBtn">Rejestracja</button>
        </form>
        <br><br><br>
            @isset($Err)
                <h5>
                    {{ $Err }}
                </h5>
            @endisset
        <br><br><br>
    </div>
</x-layout>