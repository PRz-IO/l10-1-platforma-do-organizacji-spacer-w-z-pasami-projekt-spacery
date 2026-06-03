<x-layout>
    <div class="container">

        <h1 style="margin-bottom:20px;">Dodaj psa</h1>

        <form method="POST" action="/dogs"
              style="display:flex;flex-direction:column;gap:12px;">

            @csrf

            <div>
                <label>Imię</label>
                <input type="text" name="Name" style="width:100%;padding:8px;">
            </div>

            <div>
                <label>Wiek</label>
                <input type="number" name="Age" style="width:100%;padding:8px;">
            </div>

            <div>
                <label>Zachowanie</label>
                <textarea name="Behaviour" style="width:100%;padding:8px;"></textarea>
            </div>

            <div>
                <label>Stan</label>
                <select name="State" style="width:100%;padding:8px;">
                    <option value="Ready">Ready</option>
                    <option value="Sick">Sick</option>
                    <option value="Difficult">Difficult</option>
                    <option value="Dead">Dead</option>
                </select>
            </div>

            <div>
                <label>Zdjęcie (URL)</label>
                <input type="text" name="Photo" style="width:100%;padding:8px;">
            </div>

            <button type="submit"
                    style="background:#007bff;color:white;padding:10px;border:none;border-radius:6px;">
                Dodaj psa
            </button>

        </form>

    </div>
</x-layout>