<x-layout>
    <div class="container">

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h1>Lista psów</h1>

            @if(\App\Utilities\CurrUser::IsLogged() && \App\Utilities\CurrUser::getRole() === 'Worker')
                <a href="/dogs/create"
                   style="background:#007bff;color:white;padding:10px 15px;border-radius:6px;text-decoration:none;font-weight:bold;">
                    Dodaj psa
                </a>
            @endif
        </div>

        {{-- KOMUNIKAT --}}
        @if(session('favorite_added'))
            <div style="background:#d4edda;color:#155724;padding:10px 15px;border-radius:6px;margin-bottom:15px;border:1px solid #c3e6cb;">
                Dodano psa do ulubionych
            </div>
        @endif

        @if(session('favorite_removed'))
            <div style="background:#fff3cd;color:#856404;padding:10px 15px;border-radius:6px;margin-bottom:15px;border:1px solid #ffeeba;">
                Usunięto psa z ulubionych
            </div>
        @endif

        @if($dogs->isEmpty())
            <p>Brak psów w bazie.</p>
        @else

            @foreach($dogs as $dog)

                @php
                    $isFav = in_array($dog->id, $favDogIds ?? []);
                @endphp

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

                    <!-- LEWA STRONA -->
                    <div style="display:flex;align-items:center;gap:15px;">

                        <a href="/dogs/{{ $dog->id }}" style="text-decoration:none;">
                            <div style="
                                width:80px;
                                height:80px;
                                border:2px solid #ccc;
                                border-radius:8px;
                                overflow:hidden;
                                background:#f5f5f5;
                            ">
                                <img src="{{ $dog->Photo }}" alt="{{ $dog->Name }}" style="width:100%;height:100%;object-fit:cover;">
                            </div>
                        </a>

                        <a href="/dogs/{{ $dog->id }}"
                           style="font-size:18px;font-weight:600;color:#222;text-decoration:none;">
                            {{ $dog->Name }}
                        </a>

                    </div>

                    <!-- PRAWA STRONA -->
                    <div style="display:flex;align-items:center;gap:10px;">

                        {{-- ULUBIONE --}}
                        @if(\App\Utilities\CurrUser::IsLogged()
                            && \App\Utilities\CurrUser::getRole() === 'Volunteer')

                            <form method="POST" action="/dogs/{{ $dog->id }}/favorite">
                                @csrf
                                <button type="submit" style="
                                    background:transparent;
                                    border:1px solid #ddd;
                                    padding:6px 10px;
                                    border-radius:6px;
                                    cursor:pointer;
                                    font-size:18px;
                                    line-height:1;
                                    color: {{ $isFav ? 'red' : '#999' }};
                                ">
                                    {{ $isFav ? '❤️' : '🤍' }}
                                </button>
                            </form>

                        @endif

                        <!-- SZCZEGÓŁY -->
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