<!-- 
TO DO:
Fix Front-end and fix validation
-->

<x-layout>
    <x-slot name="title">Dodaj Pracownika</x-slot>

    <form method="POST" action="{{ route('workers.store') }}">
        @csrf

        <h3>Dane konta</h3>

        <input name="Name" placeholder="Imię" required>
        <input name="Last_Name" placeholder="Nazwisko" required>
        <input name="Login" placeholder="Login" required>
        <input type="password" name="Password" placeholder="Hasło" required>
        <input name="Email" placeholder="Email" required>
        <input name="Phone_Num" placeholder="Telefon" required>

        <hr>

        <label>
            <input type="checkbox" name="Is_Admin" value="1">
            Administrator
        </label>

        <br><br>
        
        @if ($errors->any())
            <div style="color:red">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <button type="submit">Zapisz</button>
    </form>
</x-layout>