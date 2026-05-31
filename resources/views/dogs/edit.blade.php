<x-layout>
    <div class="container">
        <h1>Edytuj psa</h1>

        <form method="POST" action="/dogs/{{ $dog->id }}">
            @csrf
            @method('PUT')

            <div>
                <label>Imię psa</label>
                <input type="text" name="Name" value="{{ $dog->Name }}">
            </div>

            <div>
                <label>Wiek</label>
                <input type="number" name="Age" value="{{ $dog->Age }}">
            </div>

            <div>
                <label>Zachowanie</label>
                <textarea name="Behaviour">{{ $dog->Behaviour }}</textarea>
            </div>

            <div>
                <label>Stan</label>
                <input type="text" name="State" value="{{ $dog->State }}">
            </div>

            <div>
                <label>Zdjęcie</label>
                <input type="text" name="Photo" value="{{ $dog->Photo }}">
            </div>

            <button type="submit">Zapisz zmiany</button>
        </form>
    </div>
</x-layout>