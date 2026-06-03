<x-layout>
    <div class="container">

        <h1 style="margin-bottom:20px;">
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


        {{-- PRZYSZŁE --}}
        <h3 style="margin:20px 0 10px 0;">Przyszłe spacery</h3>

        @if($future->isEmpty())
            <p style="color:#777;">Brak przyszłych spacerów</p>
        @else

            @foreach($future as $walk)
                <div style="
                    display:flex;
                    justify-content:space-between;
                    align-items:center;
                    padding:12px 15px;
                    border:1px solid #e0e0e0;
                    border-radius:10px;
                    background:#fff;
                    margin-bottom:10px;
                ">

                    <div style="display:flex;flex-direction:column;gap:4px;">
                        <div><strong>Data:</strong> {{ $walk->Date }}</div>
                        <div><strong>Godzina:</strong> {{ $walk->Time }}</div>
                        <div><strong>Notatka:</strong> {{ $walk->Note ?? '-' }}</div>
                    </div>

                    <div style="color:#999;font-size:14px;">
                        Zaplanowany
                    </div>

                </div>
            @endforeach

        @endif


        {{-- PRZESZŁE --}}
        <h3 style="margin:25px 0 10px 0;">Odbyte spacery</h3>

        @if($past->isEmpty())
            <p style="color:#777;">Brak zakończonych spacerów</p>
        @else

            @foreach($past as $walk)
                <div style="
                    display:flex;
                    justify-content:space-between;
                    align-items:center;
                    padding:12px 15px;
                    border:1px solid #e0e0e0;
                    border-radius:10px;
                    background:#f9f9f9;
                    margin-bottom:10px;
                ">

                    <div style="display:flex;flex-direction:column;gap:4px;">
                        <div><strong>Data:</strong> {{ $walk->Date }}</div>
                        <div><strong>Godzina:</strong> {{ $walk->Time }}</div>
                        <div><strong>Notatka:</strong> {{ $walk->Note ?? '-' }}</div>
                        <div><strong>Ocena:</strong> {{ $walk->Grade ?? '-' }}</div>
                    </div>

                    <div style="color:#777;font-size:14px;">
                        Zakończony
                    </div>

                </div>
            @endforeach

        @endif

    </div>
</x-layout>