<x-layout>
    <x-slot name="title">Edytuj pracownika</x-slot>

    <div class="container">

        <h2>Edytuj pracownika</h2>

        @if ($errors->any())
            <div style="background:#f8d7da; color:#721c24; padding:10px; margin-bottom:15px; border:1px solid #f5c6cb;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('workers.update', $worker->getKey()) }}">

            @csrf
            @method('PUT')

            <div style="border:1px solid #ddd; padding:20px; border-radius:6px; background:#fff;">

                <h3 style="margin-top:0;">Dane konta</h3>

                <div style="display:grid; gap:10px;">

                    <input name="Name"
                           value="{{ old('Name', $worker->account?->Name) }}"
                           placeholder="Imię"
                           required
                           style="padding:8px;">

                    <input name="Last_Name"
                           value="{{ old('Last_Name', $worker->account?->Last_Name) }}"
                           placeholder="Nazwisko"
                           required
                           style="padding:8px;">

                    <input name="Email"
                           value="{{ old('Email', $worker->account?->Email) }}"
                           placeholder="Email"
                           required
                           style="padding:8px;">

                    <input name="Phone_Num"
                           value="{{ old('Phone_Num', $worker->account?->Phone_Num) }}"
                           placeholder="Telefon"
                           required
                           style="padding:8px;">

                </div>

                <div style="margin-top:15px;">
                    <label style="display:flex; gap:8px; align-items:center;">
                        <input type="checkbox"
                               name="Is_Admin"
                               value="1"
                               {{ $worker->Is_Admin ? 'checked' : '' }}>
                        <span>Administrator</span>
                    </label>
                </div>

                <div style="margin-top:20px; display:flex; gap:10px;">
                    <button type="submit"
                            style="padding:8px 14px; background:#007bff; color:white; border:none; border-radius:4px;">
                        Zapisz zmiany
                    </button>

                    <a href="{{ url()->previous() }}">
                        <button type="button">Anuluj</button>
                    </a>
                </div>

            </div>

        </form>

    </div>
</x-layout>