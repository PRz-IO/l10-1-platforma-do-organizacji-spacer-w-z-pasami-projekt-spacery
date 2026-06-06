<x-layout>
    <div class="container" style="display:block; text-align:left;">

        <div style="
            border:1px solid #e0e0e0;
            border-radius:12px;
            padding:20px;
            background:#fff;
            text-align:left;
        ">
            <h2 style="margin-top:0;">Profil pracownika</h2>

            <div style="display:flex;flex-direction:column;gap:8px;align-items:flex-start;text-align:left;">
                <div style="display:flex;gap:8px;align-items:flex-start;">
                    <strong>Imię:</strong> {{ $profile->account->Name ?? '-' }}
            </div>
                <div style="display:flex;gap:8px;align-items:flex-start;">
                    <strong>Nazwisko:</strong> {{ $profile->account->Last_Name ?? '-' }}
                </div>
                <div style="display:flex;gap:8px;align-items:flex-start;">
                    <strong>Email:</strong> {{ $profile->account->Email ?? '-' }}
                </div>
                <div style="display:flex;gap:8px;align-items:flex-start;">
                    <strong>Telefon:</strong> {{ $profile->account->Phone_Num ?? '-' }}
                </div>
            </div>
        </div>

    </div>
</x-layout>