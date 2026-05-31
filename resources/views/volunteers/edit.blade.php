<x-layout>
    <x-slot name="title">Edycja Wolontariusza</x-slot>

    <div class="container">
        <h2>Modyfikacja profilu: {{ $volunteer->account->Name }} {{ $volunteer->account->Last_Name }}</h2>

        <form action="{{ route('worker.volunteers.update', $volunteer->Id) }}" method="POST" style="max-width: 500px; margin-top: 20px;">
            @csrf
            @method('PUT')

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="Name" style="display: block; margin-bottom: 5px; font-weight: bold;">Imię:</label>
                <input type="text" id="Name" name="Name" value="{{ $volunteer->account->Name }}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="Last_Name" style="display: block; margin-bottom: 5px; font-weight: bold;">Nazwisko:</label>
                <input type="text" id="Last_Name" name="Last_Name" value="{{ $volunteer->account->Last_Name }}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="Email" style="display: block; margin-bottom: 5px; font-weight: bold;">Email:</label>
                <input type="email" id="Email" name="Email" value="{{ $volunteer->account->Email }}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="Phone_Num" style="display: block; margin-bottom: 5px; font-weight: bold;">Telefon:</label>
                <input type="text" id="Phone_Num" name="Phone_Num" value="{{ $volunteer->account->Phone_Num }}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <input type="checkbox" id="Is_Experienced" name="Is_Experienced" value="1" {{ $volunteer->Is_Experienced ? 'checked' : '' }} style="width: auto; cursor: pointer;">
                <label for="Is_Experienced" style="display: inline; cursor: pointer; font-weight: bold; margin-left: 5px;"> Posiada doświadczenie w wyprowadzaniu psów</label>
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" style="width: auto; padding: 10px 20px; cursor: pointer;">Zapisz zmiany</button>
                <a href="{{ route('worker.volunteers.index') }}" style="margin-left: 15px; color: #6c757d; text-decoration: none;">Wróć</a>
            </div>
        </form>
    </div>
</x-layout>