<x-layout>
    <x-slot name="title">Edycja Wolontariusza</x-slot>

    <div class="container" style="max-width: 600px; margin: 40px auto; padding: 20px;">
        <h2>Modyfikacja profilu: {{ $volunteer->account?->Name ?? 'Brak' }} {{ $volunteer->account?->Last_Name ?? 'Danych' }}</h2>

        <form action="{{ route('worker.volunteers.update', $volunteer->id) }}" method="POST" style="margin-top: 20px;">
            @csrf
            @method('PUT')

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="Name" style="display: block; font-weight: bold; margin-bottom: 5px;">Imię:</label>
                <input type="text" id="Name" name="Name" value="{{ old('Name', $volunteer->account?->Name) }}" style="width: 100%; padding: 8px;" required>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="Last_Name" style="display: block; font-weight: bold; margin-bottom: 5px;">Nazwisko:</label>
                <input type="text" id="Last_Name" name="Last_Name" value="{{ old('Last_Name', $volunteer->account?->Last_Name) }}" style="width: 100%; padding: 8px;" required>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="Email" style="display: block; font-weight: bold; margin-bottom: 5px;">Adres Email:</label>
                <input type="email" id="Email" name="Email" value="{{ old('Email', $volunteer->account?->Email) }}" style="width: 100%; padding: 8px;" required>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="Phone_Num" style="display: block; font-weight: bold; margin-bottom: 5px;">Numer telefonu:</label>
                <input type="text" id="Phone_Num" name="Phone_Num" value="{{ old('Phone_Num', $volunteer->account?->Phone_Num) }}" style="width: 100%; padding: 8px;" required>
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label style="cursor: pointer;">
                    <input type="checkbox" name="Is_Experienced" value="1" {{ $volunteer->Is_Experienced ? 'checked' : '' }}>
                    Wolontariusz posiada doświadczenie
                </label>
            </div>

            <div style="display: flex; gap: 12px; align-items: center;">
                <button type="submit" style="background-color: #007bff; color: white; padding: 10px 15px; border: none; cursor: pointer; border-radius: 4px; font-weight: bold;">Zapisz zmiany</button>
                <a href="{{ route('worker.volunteers.index') }}" style="color: #6c757d; text-decoration: none;">Anuluj</a>
            </div>
        </form>
    </div>
</x-layout>