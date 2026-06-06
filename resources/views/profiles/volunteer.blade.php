<x-layout>

    <div class="container">

        <h1 style="margin-bottom:20px;">
            Profil wolontariusza
        </h1>


        <div style="
    background:#fff;
    border:1px solid #e0e0e0;
    border-radius:12px;
    padding:20px;
    margin-bottom:20px;
    box-shadow:0 2px 6px rgba(0,0,0,0.05);
">

    <h2 style="margin:0 0 15px 0;">
        Dane profilu
    </h2>

    <div style="display:flex;flex-direction:column;gap:8px;">

        <div>
            <strong>Imię i nazwisko:</strong>
            {{ $profile->account?->Name }} {{ $profile->account?->Last_Name }}
        </div>

        <div>
            <strong>Email:</strong>
            {{ $profile->account?->Email }}
        </div>

        <div>
            <strong>Telefon:</strong>
            {{ $profile->account?->Phone_Num ?? '-' }}
        </div>

        <div>
            <strong>Login:</strong>
            {{ $profile->account?->Login }}
        </div>

        <div>
            <strong>Doświadczenie:</strong>
            {{ $profile->Is_Experienced ? 'Tak' : 'Nie' }}
        </div>

    </div>

</div>

        {{-- ulubione psy --}}


        <h2 style="margin: 20px 0 15px 0;">
            Ulubione psy
        </h2>
        @if(($favoriteDogs ?? collect())->isEmpty())

            <p style="color:#777;">
                Brak ulubionych psów
            </p>

        @else

            @foreach($favoriteDogs as $dog)

                <div style="
                    display:flex;
                    align-items:center;
                    justify-content:space-between;
                    gap:20px;
                    padding:15px;
                    margin-bottom:12px;
                    border:1px solid #e0e0e0;
                    border-radius:10px;
                    background:#fff;
                ">

                    <div style="display:flex;align-items:center;gap:15px;">
                        <a href="/dogs/{{ $dog->id }}">
                            <div style="width:80px;height:80px;border:2px solid #ccc;border-radius:8px;overflow:hidden;background:#f5f5f5;">
                                <img src="{{ $dog->Photo }}" style="width:100%;height:100%;object-fit:cover;">
                            </div>
                        </a>

                        <a href="/dogs/{{ $dog->id }}"
                           style="font-size:18px;font-weight:600;color:#222;text-decoration:none;">
                            {{ $dog->Name }}
                        </a>
                    </div>

                    <div style="display:flex;align-items:center;gap:10px;">

                        <form method="POST" action="/dogs/{{ $dog->id }}/favorite">
                            @csrf
                            <button type="submit"
                                style="background:transparent;border:1px solid #ddd;padding:6px 10px;border-radius:6px;cursor:pointer;font-size:18px;color:red;">
                                ❤️
                            </button>
                        </form>

                        <a href="/dogs/{{ $dog->id }}"
                           style="border:1px solid #ddd;padding:6px 10px;border-radius:6px;text-decoration:none;color:#333;font-size:14px;">
                            Szczegóły
                        </a>

                    </div>

                </div>

            @endforeach

        @endif

    </div>
</x-layout>