<x-layout>
    <div class="container">

        <div style="
            border:1px solid #e0e0e0;
            border-radius:12px;
            padding:20px;
            background:#fff;
        ">
            <h2 style="margin-top:0;">Profil pracownika</h2>

            <div style="display:flex;flex-direction:column;gap:6px;">
                <div><strong>Imię:</strong> {{ $profile->account->Name ?? '-' }}</div>
                <div><strong>Nazwisko:</strong> {{ $profile->account->Last_Name ?? '-' }}</div>
                <div><strong>Email:</strong> {{ $profile->account->Email ?? '-' }}</div>
                <div><strong>Telefon:</strong> {{ $profile->account->Phone_Num ?? '-' }}</div>
            </div>
        </div>

    </div>
</x-layout>