<x-layout>
    <div class="container">
        <h1>Dodaj psa</h1>

        <form method="POST" action="/dogs">
            @csrf

            <div>
                <label>Imię</label>
                <input type="text" name="Name">
            </div>

            <div>
                <label>Wiek</label>
                <input type="number" name="Age">
            </div>

            <div>
                <label>Zachowanie</label>
                <textarea name="Behaviour"></textarea>
            </div>

            <div>
                <label>Stan</label>
                <input type="text" name="State" value="Ready">
            </div>

            <div>
                <label>Zdjęcie (ścieżka)</label>
                <input type="text" name="Photo">
            </div>

            <button type="submit">Dodaj</button>
        </form>
    </div>
</x-layout>