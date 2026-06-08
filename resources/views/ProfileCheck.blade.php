<x-layout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    @endpush
    <div class="container">
        <h1 style="margin-bottom:20px; text-align:center;">
            @if ($Type=="ChPass")
                Zmiana Hasła    
            @elseif ($Type=="DelAcc")
                Usunięcie Konta
            @else
                Error
            @endif
        </h1>
        <form method="POST" @if ($Type=="ChPass")
            action="{{ route('profile.change') }}"
        @elseif ($Type=="DelAcc")
            action="{{ route('profile.delete') }}"
        @else
            action="#"
        @endif>
        @csrf
            <label for="password">@if ($Type=="ChPass")Obecne @endif Hasło:</label>
            <input type="password" class="Inp" id="password" name="password"><br><br>
            @if ($Type=="ChPass")
            @method('PUT')
            <label for="newpassword">Nowe Hasło:</label>
            <input type="password" id="newpassword" name="newpassword" class="Inp"><br><br>
            <label for="rnewpassword">Powtórz Nowe Hasło:</label>
            <input type="password" id="rnewpassword" name="rnewpassword" class="Inp"><br><br>
            <button type="submit" class="Btn">Zmień Hasło</button>
            @elseif ($Type=="DelAcc")
            @method('DELETE')   
                <button type="submit" class="Btn">Usuń Konto</button>
            @endif
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