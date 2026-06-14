<x-layout>
    <div id="top"></div>
    <div class="container">

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">

            <h1>Lista psów</h1>
            

            @if(\App\Utilities\CurrUser::IsLogged() && \App\Utilities\CurrUser::getRole() === 'Worker')
                <a href="/dogs/create"
                   style="background:#fff;color:#5f6f86;padding:10px 15px;border-radius:6px;text-decoration:none;font-weight:600;border:1px solid #e1e6ef;">
                    Dodaj psa
                </a>
            @endif
        </div>

@if(
    !$readyDogs->isEmpty()
    || !$difficultDogs->isEmpty()
    || !$sickDogs->isEmpty()
    || (
        \App\Utilities\CurrUser::IsLogged()
        && \App\Utilities\CurrUser::getRole() === 'Worker'
        && !$deadDogs->isEmpty()
    )
)
        <div style="
    margin-bottom:20px;
    padding:10px 14px;
    background:#fff;
    border:1px solid #ececec;
    border-radius:8px;
    font-size:14px;
    display:flex;
    align-items:center;
    gap:12px;
">

    <div style="
        color:#888;
        font-weight:600;
        white-space:nowrap;
    ">
        Przejdź do:
    </div>

    <div style="
        display:flex;
        flex-wrap:wrap;
        gap:6px;
        flex:1;
    ">
        @if(!$readyDogs->isEmpty())
        <a href="#ready" style="
            color:#666;
            text-decoration:none;
            background:#fff;
            border:1px solid #ddd;
            border-radius:5px;
            padding:3px 8px;
        ">
            Gotowe na spacer
        </a>
        @endif

        @if(!$difficultDogs->isEmpty())
        <a href="#difficult" style="
            color:#666;
            text-decoration:none;
            background:#fff;
            border:1px solid #ddd;
            border-radius:5px;
            padding:3px 8px;
        ">
            Wymagające doświadczonej opieki
        </a>
        @endif
        
        @if(!$sickDogs->isEmpty())
        <a href="#sick" style="
            color:#666;
            text-decoration:none;
            background:#fff;
            border:1px solid #ddd;
            border-radius:5px;
            padding:3px 8px;
        ">
            Pod opieką weterynarza
        </a>
        @endif

        @if(\App\Utilities\CurrUser::IsLogged() &&
            \App\Utilities\CurrUser::getRole() === 'Worker' && !$deadDogs->isEmpty())

            <a href="#dead" style="
                color:#666;
                text-decoration:none;
                background:#fff;
                border:1px solid #ddd;
                border-radius:5px;
                padding:3px 8px;
            ">
                Archiwum
            </a>

        @endif

        </div>


</div>
@endif

        @if(session('favorite_added'))
            <div style="background:#d4edda;color:#155724;padding:10px 15px;border-radius:6px;margin-bottom:15px;border:1px solid #c3e6cb;">
                Dodano psa do ulubionych
            </div>
        @endif

        @if(session('favorite_removed'))
            <div style="background:#e7f3ff;color:#1f4e79;padding:10px 15px;border-radius:6px;margin-bottom:15px;border:1px solid #cfe8ff;">
                Usunięto psa z ulubionych
            </div>
        @endif


        @if(($favoriteDogs->isEmpty() ?? true) && ($otherDogs->isEmpty() ?? true))
            <p>Brak psów w bazie</p>
        @else


            @if(count($favoriteDogs ?? []) > 0)

                @foreach($favoriteDogs as $dog)

                    @php $isFav = true; @endphp

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
                            <a href="/dogs/{{ $dog->id }}" style="text-decoration:none;">
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
                                <button type="submit" style="background:transparent;border:1px solid #ddd;padding:6px 10px;border-radius:6px;cursor:pointer;font-size:18px;line-height:1;color:red;">
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


                @if(count($otherDogs ?? []) > 0)
                <div style="margin:25px 0;border-top:2px solid #e0e0e0;"></div>
                @endif

            @endif

                @if(!$readyDogs->isEmpty())
                <h2 id="ready" style="text-align:left;color:#777;font-weight:600;font-size:14px;margin:35px 0 15px 0;letter-spacing:.2px;">
                Gotowe na spacer
                </h2>

                @foreach($readyDogs as $dog)

                    @php
                    $isFav = false;
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

                    <div style="display:flex;align-items:center;gap:15px;">

                        <a href="/dogs/{{ $dog->id }}" style="text-decoration:none;">
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

                        @if(\App\Utilities\CurrUser::IsLogged() && \App\Utilities\CurrUser::getRole() === 'Volunteer')
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
                                    color:#999;
                                ">
                                    🤍
                                </button>
                            </form>
                        @endif

                        <a href="/dogs/{{ $dog->id }}"
                           style="border:1px solid #ddd;padding:6px 10px;border-radius:6px;text-decoration:none;color:#333;font-size:14px;">
                            Szczegóły
                        </a>

                    </div>

                </div>

                @endforeach
                @endif


                @if(!$difficultDogs->isEmpty())
                <h2 id="difficult" style="text-align:left;color:#777;font-weight:600;font-size:14px;margin:35px 0 15px 0;letter-spacing:.2px;">
                Wymagające doświadczonej opieki
                </h2>

                @foreach($difficultDogs as $dog)

                    @php
                    $isFav = false;
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

                    <div style="display:flex;align-items:center;gap:15px;">

                        <a href="/dogs/{{ $dog->id }}" style="text-decoration:none;">
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

                        @if(\App\Utilities\CurrUser::IsLogged() && \App\Utilities\CurrUser::getRole() === 'Volunteer')
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
                                    color:#999;
                                ">
                                    🤍
                                </button>
                            </form>
                        @endif

                        <a href="/dogs/{{ $dog->id }}"
                           style="border:1px solid #ddd;padding:6px 10px;border-radius:6px;text-decoration:none;color:#333;font-size:14px;">
                            Szczegóły
                        </a>

                    </div>

                </div>

                @endforeach
                @endif

                @if(!$sickDogs->isEmpty())
                <h2 id="sick" style="text-align:left;color:#777;font-weight:600;font-size:14px;margin:35px 0 15px 0;letter-spacing:.2px;">
                    Pod opieką weterynarza
                </h2>

                @foreach($sickDogs as $dog)

                    @php
                    $isFav = false;
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

                    <div style="display:flex;align-items:center;gap:15px;">

                        <a href="/dogs/{{ $dog->id }}" style="text-decoration:none;">
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

                        @if(\App\Utilities\CurrUser::IsLogged() && \App\Utilities\CurrUser::getRole() === 'Volunteer')
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
                                    color:#999;
                                ">
                                    🤍
                                </button>
                            </form>
                        @endif

                        <a href="/dogs/{{ $dog->id }}"
                           style="border:1px solid #ddd;padding:6px 10px;border-radius:6px;text-decoration:none;color:#333;font-size:14px;">
                            Szczegóły
                        </a>

                    </div>

                </div>

                @endforeach
                @endif


                @if(\App\Utilities\CurrUser::IsLogged() && \App\Utilities\CurrUser::getRole() === 'Worker' && !$deadDogs->isEmpty())


                <h2 id="dead" style="text-align:left;color:#777;font-weight:600;font-size:14px;margin:35px 0 15px 0;letter-spacing:.2px;">
                    Archiwum
                </h2>


                @foreach($deadDogs as $dog)

                    @php
                    $isFav = false;
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

                    <div style="display:flex;align-items:center;gap:15px;">

                        <a href="/dogs/{{ $dog->id }}" style="text-decoration:none;">
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

                        @if(\App\Utilities\CurrUser::IsLogged() && \App\Utilities\CurrUser::getRole() === 'Volunteer')
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
                                    color:#999;
                                ">
                                    🤍
                                </button>
                            </form>
                        @endif

                        <a href="/dogs/{{ $dog->id }}"
                           style="border:1px solid #ddd;padding:6px 10px;border-radius:6px;text-decoration:none;color:#333;font-size:14px;">
                            Szczegóły
                        </a>

                    </div>

                </div>

                @endforeach

            @endif

        @endif

    </div>
    
    <a href="#top"
   style="
        position:fixed;
        bottom:20px;
        right:20px;

        width:38px;
        height:38px;

        display:flex;
        align-items:center;
        justify-content:center;

        text-decoration:none;

        border:1px solid #ddd;
        border-radius:6px;

        background:#fff;
        color:#666;

        font-size:18px;
        font-weight:600;

        z-index:1000;
   ">
    ↑
</a>
</x-layout>