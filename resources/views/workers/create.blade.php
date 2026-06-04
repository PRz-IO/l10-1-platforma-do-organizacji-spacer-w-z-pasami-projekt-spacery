<x-layout>
    <x-slot name="title">Dodaj pracownika</x-slot>

    <div class="container">

        <h2>Dodaj pracownika</h2>

        @if ($errors->any())
            <div style="
                background:#f8d7da;
                color:#721c24;
                padding:10px;
                margin-bottom:15px;
                border:1px solid #f5c6cb;
            ">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('workers.store') }}">
            @csrf

            <div style="
                border:1px solid #ddd;
                padding:20px;
                border-radius:6px;
                background:#fff;
            ">

                <h3 style="margin-top:0;">Dane konta</h3>

                <div style="display:grid; gap:10px;">

                    <input
                        name="Name"
                        value="{{ old('Name') }}"
                        placeholder="Imię"
                        required
                        style="padding:8px;"
                    >

                    <input
                        name="Last_Name"
                        value="{{ old('Last_Name') }}"
                        placeholder="Nazwisko"
                        required
                        style="padding:8px;"
                    >

                    <input
                        name="Login"
                        value="{{ old('Login') }}"
                        placeholder="Login"
                        required
                        style="padding:8px;"
                    >

                    <input
                        type="email"
                        name="Email"
                        value="{{ old('Email') }}"
                        placeholder="Email"
                        required
                        style="padding:8px;"
                    >

                    <input
                        name="Phone_Num"
                        value="{{ old('Phone_Num') }}"
                        placeholder="Telefon"
                        required
                        style="padding:8px;"
                    >

                </div>

                <p style="margin-top:15px; color:#666;">
                    Hasło zostanie wygenerowane automatycznie.
                </p>

                <div style="margin-top:15px;">
                    <label style="display:flex; gap:8px; align-items:center;">
                        <input
                            type="checkbox"
                            name="Is_Admin"
                            value="1"
                            {{ old('Is_Admin') ? 'checked' : '' }}
                        >
                        Administrator
                    </label>
                </div>

                <div style="margin-top:20px; display:flex; gap:10px;">

                    <button
                        type="submit"
                        style="
                            padding:8px 14px;
                            background:#007bff;
                            color:white;
                            border:none;
                            border-radius:4px;
                        "
                    >
                        Dodaj pracownika
                    </button>

                    <a href="{{ route('workers.index') }}"
                       style="
                            display:inline-block;
                            padding:8px 14px;
                            border:1px solid #ddd;
                            background:#f8f9fa;
                            color:black;
                            text-decoration:none;
                       ">
                        Anuluj
                    </a>

                </div>

            </div>
        </form>

    </div>
</x-layout>