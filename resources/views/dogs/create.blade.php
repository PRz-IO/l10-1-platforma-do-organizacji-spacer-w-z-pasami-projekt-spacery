<x-layout>
    <div class="container">

        <h1 style="margin-bottom:20px;color:#2f2f2f;font-size:26px;font-weight:700;letter-spacing:.2px;text-align:left;">Dodaj psa</h1>
        <div style="margin:20px 0 25px 0;border-top:2px solid #e0e0e0;"></div>
        <form method="POST" action="/dogs"

            enctype="multipart/form-data"
              style="display:flex;flex-direction:column;gap:12px;text-align:left;">

            @csrf

            <div>
                <label style="display:block;margin-bottom:6px;color:#4a4a4a;font-size:15px;font-weight:600;letter-spacing:.2px;">Imię</label>
                <input type="text" name="Name" style="
                width:100%;
                padding:10px;
                border:1px solid #e2e2e2;
                border-radius:6px;
                background:#fff;
                color:#333;
                font-size:14px;
                outline:none;
           "
           onfocus="this.style.borderColor='#c9d6ea'"
           onblur="this.style.borderColor='#e2e2e2'">
            </div>

            <div>
                <label style="display:block;margin-bottom:6px;color:#4a4a4a;font-size:15px;font-weight:600;letter-spacing:.2px;">Wiek</label>
                <input type="number" name="Age" style="
                width:100%;
                padding:10px;
                border:1px solid #e2e2e2;
                border-radius:6px;
                background:#fff;
                color:#333;
                font-size:14px;
                outline:none;
           "
           onfocus="this.style.borderColor='#c9d6ea'"
           onblur="this.style.borderColor='#e2e2e2'">
            </div>

            <div>
                <label style="display:block;margin-bottom:6px;color:#4a4a4a;font-size:15px;font-weight:600;letter-spacing:.2px;">Zachowanie</label>
                <textarea name="Behaviour" style="
                width:100%;
                padding:10px;
                border:1px solid #e2e2e2;
                border-radius:6px;
                background:#fff;
                color:#333;
                font-size:14px;
                outline:none;
           "
           onfocus="this.style.borderColor='#c9d6ea'"
           onblur="this.style.borderColor='#e2e2e2'"></textarea>
            </div>

            <div>
                <label style="display:block;margin-bottom:6px;color:#4a4a4a;font-size:15px;font-weight:600;letter-spacing:.2px;">Stan</label>
                <select name="State" style="
                width:100%;
                padding:10px;
                border:1px solid #e2e2e2;
                border-radius:6px;
                background:#fff;
                color:#333;
                font-size:14px;
                outline:none;
           "
           onfocus="this.style.borderColor='#c9d6ea'"
           onblur="this.style.borderColor='#e2e2e2'">
                    <option value="Ready">Gotowy na spacer</option>
                    <option value="Sick">Pod opieką weterynarza</option>
                    <option value="Difficult">Wymaga doświadczonej opieki</option>
                    <option value="Dead">W lepszym miejscu</option>
                </select>
            </div>

            <div>
                <label style="display:block;margin-bottom:6px;color:#4a4a4a;font-size:15px;font-weight:600;letter-spacing:.2px;">Zdjęcie</label>
                <input type="file" name="Photo" accept="image/*">
            </div>

            <button type="submit"
                    style="background:#007bff;color:white;padding:10px;border:none;border-radius:6px;">
                Dodaj psa
            </button>

        </form>

    </div>
</x-layout>