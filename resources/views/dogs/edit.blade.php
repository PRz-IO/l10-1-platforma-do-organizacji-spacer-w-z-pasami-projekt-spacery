<x-layout>
    <div class="container">

        <h1 style="margin-bottom:20px;">Edytuj psa</h1>

        <form method="POST" action="/dogs/{{ $dog->id }}"
              style="display:flex;flex-direction:column;gap:12px;">

            @csrf
            @method('PUT')

            <div>
                <label>Imię</label>
                <input type="text" name="Name"
                       value="{{ $dog->Name }}"
                       style="width:100%;padding:8px;">
            </div>

            <div>
                <label>Wiek</label>
                <input type="number" name="Age"
                       value="{{ $dog->Age }}"
                       style="width:100%;padding:8px;">
            </div>

            <div>
                <label>Zachowanie</label>
                <textarea name="Behaviour"
                          style="width:100%;padding:8px;">{{ $dog->Behaviour }}</textarea>
            </div>

            <div>
                <label>Stan</label>
                <select name="State" style="width:100%;padding:8px;">
                    <option value="Ready" @selected($dog->State === 'Ready')>Ready</option>
                    <option value="Sick" @selected($dog->State === 'Sick')>Sick</option>
                    <option value="Difficult" @selected($dog->State === 'Difficult')>Difficult</option>
                    <option value="Dead" @selected($dog->State === 'Dead')>Dead</option>
                </select>
            </div>

            <div>
                <label>Zdjęcie (URL)</label>
                <input type="text" name="Photo"
                       value="{{ $dog->Photo }}"
                       style="width:100%;padding:8px;">
            </div>

            <button type="submit"
                    style="background:#007bff;color:white;padding:10px;border:none;border-radius:6px;">
                Zapisz zmiany
            </button>

        </form>

    </div>
</x-layout>