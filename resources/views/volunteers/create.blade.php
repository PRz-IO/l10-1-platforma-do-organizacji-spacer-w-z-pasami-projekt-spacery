<x-layout>
    <x-slot name="title">Dodaj Nowego Wolontariusza</x-slot>

    <div class="container" style="max-width: 600px; margin: 40px auto;">
        <h3 style="margin-bottom: 25px; font-weight: bold; color: #333;">Dodaj nowego wolontariusza do bazy</h3>

        <form action="{{ route('worker.volunteers.store') }}" method="POST">
            @csrf

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="Name" style="display: block; font-weight: bold; margin-bottom: 5px; color: #444;">Imię:</label>
                <input type="text" name="Name" id="Name" value="{{ old('Name') }}" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;" required>
                @error('Name')
                    <span style="color: #f44336; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="Last_Name" style="display: block; font-weight: bold; margin-bottom: 5px; color: #444;">Nazwisko:</label>
                <input type="text" name="Last_Name" id="Last_Name" value="{{ old('Last_Name') }}" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;" required>
                @error('Last_Name')
                    <span style="color: #f44336; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="Login" style="display: block; font-weight: bold; margin-bottom: 5px; color: #444;">Login konta:</label>
                <input type="text" name="Login" id="Login" value="{{ old('Login') }}" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;" required>
                @error('Login')
                    <span style="color: #f44336; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="Password" style="display: block; font-weight: bold; margin-bottom: 5px; color: #444;">Hasło startowe:</label>
                <input type="password" name="Password" id="Password" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;" required>
                @error('Password')
                    <span style="color: #f44336; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="Password_confirmation" style="display: block; font-weight: bold; margin-bottom: 5px; color: #444;">Powtórz hasło:</label>
                <input type="password" name="Password_confirmation" id="Password_confirmation" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;" required>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="Email" style="display: block; font-weight: bold; margin-bottom: 5px; color: #444;">Adres Email:</label>
                <input type="email" name="Email" id="Email" value="{{ old('Email') }}" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;" required>
                @error('Email')
                    <span style="color: #f44336; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="Phone_Num" style="display: block; font-weight: bold; margin-bottom: 5px; color: #444;">Numer telefonu:</label>
                <input type="text" name="Phone_Num" id="Phone_Num" value="{{ old('Phone_Num') }}" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;" required>
                @error('Phone_Num')
                    <span style="color: #f44336; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label style="font-weight: normal; cursor: pointer; color: #444; display: flex; align-items: center;">
                    <input type="checkbox" name="Is_Experienced" id="Is_Experienced" {{ old('Is_Experienced') ? 'checked' : '' }} style="margin-right: 8px; width: 16px; height: 16px;">
                    Wolontariusz posiada doświadczenie (zaznacz jeśli tak)
                </label>
            </div>

            <div style="display: flex; gap: 12px; align-items: center;">
                <button type="submit" style="background-color: #007bff; color: white; padding: 10px 24px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 15px;">Zapisz w bazie</button>
                <a href="{{ route('worker.volunteers.index') }}" style="color: #6c757d; text-decoration: none; padding: 10px 20px; border: 1px solid #ccc; border-radius: 4px; font-weight: bold; font-size: 15px; background: transparent;">Anuluj</a>
            </div>
        </form>
    </div>
</x-layout>