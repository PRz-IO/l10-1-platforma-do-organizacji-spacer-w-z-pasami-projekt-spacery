<x-layout>
    <div class="container">

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">
            <h1>{{ $dog->Name }}</h1>

            <div style="display:flex;gap:10px;align-items:center;">

                @if(\App\Utilities\CurrUser::IsLogged() && \App\Utilities\CurrUser::getRole() === 'Volunteer')

                    @php
                        $volunteerId = \App\Models\Volunteer::where(
                            'account_id',
                            \App\Utilities\CurrUser::getId()
                        )->first()?->id;

                        $isFav = false;

                        if ($volunteerId) {
                            $isFav = \App\Models\Fav_Dog::where('dog_id', $dog->id)
                                ->where('volunteer_id', $volunteerId)
                                ->exists();
                        }
                    @endphp

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


                @if(\App\Utilities\CurrUser::IsLogged() && \App\Utilities\CurrUser::getRole() === 'Worker')
                    <a href="/dogs/{{ $dog->id }}/edit"
                       style="border:1px solid #ddd;padding:6px 10px;border-radius:6px;text-decoration:none;color:#333;">
                        Edytuj
                    </a>
                @endif

                <a href="/dogs/{{ $dog->id }}/walks"
                   style="border:1px solid #ddd;padding:6px 10px;border-radius:6px;text-decoration:none;color:#333;">
                    Spacery
                </a>

            </div>
        </div>

        <div style="display:flex;gap:25px;align-items:flex-start;">

            <div style="
                width:220px;
                height:220px;
                border:2px solid #ccc;
                border-radius:10px;
                overflow:hidden;
                background:#f5f5f5;
                flex-shrink:0;
            ">
                <img src="{{ $dog->Photo }}"
                     alt="{{ $dog->Name }}"
                     style="width:100%;height:100%;object-fit:cover;">
            </div>


            <div style="display:flex;flex-direction:column;gap:12px;text-align:left;">

                <div style="font-size:16px;">
                    <strong>Imię:</strong> {{ $dog->Name }}
                </div>

                <div style="font-size:16px;">
                    <strong>Wiek:</strong> {{ $dog->Age }}
                </div>

                <div style="font-size:16px;">
                    <strong>Stan:</strong> {{ \App\Utilities\DogState::label($dog->State) }}
                </div>

                <div style="
                    max-width:500px;
                    font-size:16px;
                    text-align:justify;
                ">
                    <strong>Zachowanie:</strong><br>
                    {{ $dog->Behaviour }}
                </div>

            </div>

        </div>

    </div>
</x-layout>