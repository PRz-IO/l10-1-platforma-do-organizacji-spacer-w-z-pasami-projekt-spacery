<x-layout>
    <div class="container">

        <h1 style="margin-bottom:25px;">
            Spacery psa: {{ $dog->Name }}
        </h1>

        @php
            $now = now();

            $future = $walks->filter(function ($w) use ($now) {
                return \Carbon\Carbon::parse($w->Date . ' ' . $w->Time)->greaterThan($now);
            });

            $past = $walks->filter(function ($w) use ($now) {
                return \Carbon\Carbon::parse($w->Date . ' ' . $w->Time)->lessThanOrEqualTo($now);
            });
        @endphp


        {{-- przyszłe spacery --}}
        <div style="
            border:1px solid #e0e0e0;
            border-radius:10px;
            padding:20px;
            background:#fff;
            margin-bottom:25px;
        ">

            <h3 style="
                margin:0 0 15px 0;
                text-align:left;
            ">
                Przyszłe spacery
            </h3>

            @if($future->isEmpty())

                <p style="
                    color:#777;
                    margin:0;
                    text-align:left;
                ">
                    Brak przyszłych spacerów
                </p>

            @else

                @foreach($future as $walk)

                    <div style="
                        display:flex;
                        justify-content:space-between;
                        align-items:flex-start;
                        padding:12px 15px;
                        border:1px solid #e0e0e0;
                        border-radius:10px;
                        background:#fff;
                        margin-bottom:10px;
                    ">

                        <div style="display:flex;flex-direction:column;gap:4px;align-items:flex-start;text-align:left;">
                            @if(\App\Utilities\CurrUser::getRole() === 'Worker')
                                <div>
                                    <strong>Wolontariusz:</strong>
                                    {{ $walk->volunteer_name }} {{ $walk->volunteer_surname }}
                                </div>
                            @endif
                            <div><strong>Data:</strong> {{ $walk->Date }}</div>
                            <div><strong>Godzina:</strong> {{ $walk->Time }}</div>
                        </div>

                        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px;">


                            @if(\App\Utilities\CurrUser::IsLogged()
                                && \App\Utilities\CurrUser::getRole() === 'Volunteer')

                                <form
                                    method="POST"
                                    action="{{ route('dogs.walks.cancel', $walk->id) }}"
                                    onsubmit="return confirm('Czy na pewno chcesz anulować ten spacer?');"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        style="
                                            background:#dc3545;
                                            color:white;
                                            border:none;
                                            padding:6px 10px;
                                            border-radius:6px;
                                            cursor:pointer;
                                            font-size:13px;
                                        ">
                                        Anuluj
                                    </button>
                                </form>

                            @endif

                        </div>

                    </div>

                @endforeach

            @endif

        </div>



        {{-- odbyte spacery --}}
        <div style="
            border:1px solid #e0e0e0;
            border-radius:10px;
            padding:20px;
            background:#fafafa;
            margin-bottom:25px;
        ">

            <h3 style="
                margin:0 0 15px 0;
                text-align:left;
            ">
                Odbyte spacery
            </h3>

            @if($past->isEmpty())

                <p style="
                    color:#777;
                    margin:0;
                    text-align:left;
                ">
                    Brak zakończonych spacerów
                </p>

            @else

                @foreach($past as $walk)

                    @php
                        $walkDateTime = \Carbon\Carbon::parse($walk->Date . ' ' . $walk->Time);
                        $canAddNote = $walkDateTime->greaterThanOrEqualTo(now()->subHours(72));
                    @endphp

                    <div style="
                        display:flex;
                        justify-content:space-between;
                        align-items:flex-start;
                        padding:12px 15px;
                        border:1px solid #e0e0e0;
                        border-radius:10px;
                        background:#fff;
                        margin-bottom:10px;
                    ">

                        <div style="display:flex;flex-direction:column;gap:4px;align-items:flex-start;text-align:left;">
                            @if(\App\Utilities\CurrUser::getRole() === 'Worker')
                                <div>
                                    <strong>Wolontariusz:</strong>
                                    {{ $walk->volunteer_name }} {{ $walk->volunteer_surname }}
                                </div>
                            @endif
                            <div><strong>Data:</strong> {{ $walk->Date }}</div>
                            <div><strong>Godzina:</strong> {{ $walk->Time }}</div>
                            <div><strong>Notatka:</strong> {{ $walk->Note ?? '-' }}</div>
                            @if(!is_null($walk->Grade))
                            <div><strong>Ocena:</strong> {{ $walk->Grade }}</div>
                            @endif
                        </div>

                        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px;">

                            @if($canAddNote && \App\Utilities\CurrUser::IsLogged() && \App\Utilities\CurrUser::getRole() === 'Volunteer' && empty($walk->Note))

                                <form method="POST"
                                      action="{{ route('dogs.walks.note', $walk->id) }}"
                                      style="display:flex;gap:5px;align-items:center;">

                                    @csrf

                                    <input type="text"
                                           name="note"
                                           placeholder="Dodaj notatkę"
                                           style="
                                               padding:6px;
                                               border:1px solid #ccc;
                                               border-radius:6px;
                                               font-size:13px;
                                           ">

                                    <button type="submit"
                                            style="
                                                background:#28a745;
                                                color:white;
                                                border:none;
                                                padding:6px 10px;
                                                border-radius:6px;
                                                cursor:pointer;
                                                font-size:13px;
                                            ">
                                        Zapisz
                                    </button>

                                </form>

                            @endif


                            @if(\App\Utilities\CurrUser::IsLogged()
                                && \App\Utilities\CurrUser::getRole() === 'Worker'
                                && is_null($walk->Grade))

                                <form method="POST"
                                    action="{{ route('dogs.walks.grade', $walk->id) }}"
                                    style="display:flex;gap:5px;align-items:center;">

                                    @csrf

                                    <select name="grade"
                                            style="
                                                padding:6px;
                                                border:1px solid #ccc;
                                                border-radius:6px;
                                                font-size:13px;
                                            ">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>

                                    <button type="submit"
                                            style="
                                                background:#ffc107;
                                                color:#000;
                                                border:none;
                                                padding:6px 10px;
                                                border-radius:6px;
                                                cursor:pointer;
                                                font-size:13px;
                                            ">
                                        Oceń
                                    </button>

                                </form>

                            @endif

                        </div>


                    </div>

                @endforeach

            @endif

        </div>



        {{-- nowy spacer --}}
        @if(\App\Utilities\CurrUser::IsLogged()
            && \App\Utilities\CurrUser::getRole() === 'Volunteer')

            <div style="
                border-top:2px solid #e0e0e0;
                padding-top:20px;
                margin-top:10px;
            ">

                <h4 style="
                    margin:0 0 20px 0;
                    color:#000;
                    font-size:22px;
                    font-weight:700;
                ">
                    Zarezerwuj nowy spacer
                </h4>

                <form action="{{ route('dogs.walks.reserve', $dog->id) }}"
                      method="POST">

                    @csrf

                    <div style="margin-bottom:15px;">

                        <label style="display:block;margin-bottom:5px;color:#555;font-size:15px;">
                            Data spaceru
                        </label>

                        <input
                            type="date"
                            name="walk_date"
                            required
                            min="{{ date('Y-m-d') }}"
                            style="
                                padding:8px;
                                border:1px solid #ccc;
                                border-radius:6px;
                            ">

                    </div>

                    <div style="margin-bottom:15px;">

                        <label style="display:block;margin-bottom:5px;color:#555;font-size:15px;">
                            Godzina spaceru
                        </label>

                        <select
                            name="walk_time"
                            required
                            style="
                                padding:8px;
                                border:1px solid #ccc;
                                border-radius:6px;
                            ">

                            @php
                                $times = [
                                    '08:00',
                                    '09:00',
                                    '10:00',
                                    '11:00',
                                    '12:00',
                                    '13:00',
                                    '14:00',
                                    '15:00',
                                    '16:00',
                                    '17:00'
                                ];
                            @endphp

                            @foreach($times as $time)
                                <option value="{{ $time }}:00">
                                    {{ $time }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                    <button type="submit"
                            style="
                                background:#007bff;
                                color:white;
                                border:none;
                                padding:10px 15px;
                                border-radius:6px;
                                cursor:pointer;
                            ">
                        Zarezerwuj spacer
                    </button>

                </form>

            </div>

        @endif

    </div>
</x-layout>