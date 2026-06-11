<!-- 
TO DO:
actually create showing single worker info
-->
<x-layout>
    @php
        $state = $worker->account?->Acc_State ?? 'Unknown';
        $stateLower = strtolower($state);

        $map = [
            'active' => ['#d4edda', '#155724', '#c3e6cb'],
            'blocked' => ['#f8d7da', '#721c24', '#f5c6cb'],
            'deleted' => ['#e2e3e5', '#383d41', '#d6d8db'],
            'default' => ['#fff3cd', '#856404', '#ffeeba'],
        ];

        [$bg, $color, $border] = $map[$stateLower] ?? $map['default'];
    @endphp
    <div class="container">
    <h2>Pełne informacje o pracowniku</h2>

    @if(session('success'))
        <div style="background:#d4edda; padding:10px; margin-bottom:15px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('generated_password'))
        <div style="background:#fff3cd; padding:10px; margin-bottom:15px;">
            <strong>Wygenerowane hasło:</strong>
            {{ session('generated_password') }}
        </div>
    @endif

    <div style="border:1px solid #ddd; padding:20px; border-radius:6px; margin-bottom:20px;">

        <h3 style="margin-top:0;">
            {{ $worker->account?->Name }}
            {{ $worker->account?->Last_Name }}

            <span
                style="
                    margin-left:8px;
                    padding:3px 8px;
                    background: {{ $bg }};
                    color: {{ $color }};
                    border:1px solid {{ $border }};
                    border-radius:4px;
                    font-size:12px;
                ">
                {{ $state }}
            </span>
        </h3>
        @if(\App\Utilities\CurrUser::IsLogged() && 
            \App\Utilities\CurrUser::getRole()=="Worker" &&
            \App\Utilities\CurrUser::getAcc_State() !="Pending" &&
            \App\Utilities\CurrUser::getParam() ==True
        )
            <p><strong>Login:</strong> {{ $worker->account?->Login }}</p>
        @endif
        <p><strong>Email:</strong> {{ $worker->account?->Email }}</p>
        <p><strong>Telefon:</strong> {{ $worker->account?->Phone_Num }}</p>

        <p>
            <strong>Administrator:</strong>
            {{ $worker->Is_Admin ? 'Tak' : 'Nie' }}
        </p>

    </div>
    <div style="
        margin-top:15px;
        display:flex;
        flex-wrap:wrap;
        gap:8px;
    ">

        @if(\App\Utilities\CurrUser::IsLogged() && 
            \App\Utilities\CurrUser::getRole()=="Worker" &&
            \App\Utilities\CurrUser::getAcc_State() !="Pending" &&
            \App\Utilities\CurrUser::getParam() ==True
        )

            <a href="{{ route('workers.edit', $worker->getKey()) }}">
                <button type="button">
                    Edytuj
                </button>
            </a>

            <form method="POST"
                action="{{ route('workers.reset-password', $worker->getKey()) }}"
                onsubmit="return confirm('Zresetować hasło pracownikowi?')">
                @csrf

                <button type="submit"
                        style="background:#ffc107;">
                    Reset hasła
                </button>
            </form>

            @if($worker->account?->Acc_State === 'Active')

                <form method="POST"
                    action="{{ route('workers.block', $worker->getKey()) }}"
                    onsubmit="return confirm('Zablokować pracownika?')">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            style="background:#fd7e14; color:white;">
                        Zablokuj
                    </button>
                </form>

            @elseif($worker->account?->Acc_State === 'Blocked')

                <form method="POST"
                    action="{{ route('workers.unblock', $worker->getKey()) }}"
                    onsubmit="return confirm('Odblokować pracownika?')">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            style="background:#28a745; color:white;">
                        Odblokuj
                    </button>
                </form>

            @elseif($worker->account?->Acc_State === 'Pending')

                <form method="POST"
                    action="{{ route('workers.approve', $worker->getKey()) }}"
                    onsubmit="return confirm('Zatwierdzić pracownika?')">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            style="background:#28a745; color:white;">
                        Zatwierdź
                    </button>
                </form>

            @endif

            <form method="POST"
                action="{{ route('workers.destroy', $worker->getKey()) }}"
                onsubmit="return confirm('Usunąć pracownika?')">

                @csrf
                @method('DELETE')

                <button type="submit"
                        style="background:red; color:white;">
                    Usuń
                </button>
            </form>
            
        @endif

    </div>

    <hr style="margin:30px 0;">

    <h3>Harmonogramy przypisane do pracownika</h3>

    <br>

    @if($schedules->isEmpty())

        <div style="border:1px solid #ddd; padding:15px; border-radius:6px;">
            Brak przypisanych harmonogramów.
        </div>
    @else

    <table
        style="
            width:100%;
            border-collapse:collapse;
            background:white;
        ">
    <thead>
        <tr style="background:#f8f9fa;">
            <th style="padding:10px; border:1px solid #ddd;">Data</th>
            <th style="padding:10px; border:1px solid #ddd;">Godzina</th>
            <th style="padding:10px; border:1px solid #ddd;">Wolontariusz</th>
            <th style="padding:10px; border:1px solid #ddd;">Pies</th>
            <th style="padding:10px; border:1px solid #ddd;">Ocena</th>
        </tr>
    </thead>

        <tbody>
        @foreach($schedules as $schedule)
        <tr>
            <td style="padding:10px; border:1px solid #ddd;">
                {{ $schedule->Date }}
            </td>

            <td style="padding:10px; border:1px solid #ddd;">
                {{ $schedule->Time }}
            </td>
            
            <td style="padding:10px; border:1px solid #ddd;">
                {{ $schedule->volunteer?->account?->Name ?? '-' }}
                {{ $schedule->volunteer?->account?->Last_Name ?? '' }}
            </td>

            <td style="padding:10px; border:1px solid #ddd;">
                {{ $schedule->dog?->Name ?? '-' }}
            </td>

            <td style="padding:10px; border:1px solid #ddd; text-align: center;">
                
                @if($schedule->Grade)
                    <div style="color:gold; font-size:16px; margin-bottom: 10px;">
                        {{ str_repeat('★', $schedule->Grade) }}
                        <span style="color:#ccc;">
                            {{ str_repeat('☆', 5 - $schedule->Grade) }}
                        </span>
                        <small style="color:#333;">
                            ({{ $schedule->Grade }}/5)
                        </small>
                    </div>
                @else
                    <div style="margin-bottom: 10px;">-</div>
                @endif

                <form method="GET" action="{{ route('walks.details', $schedule->id) }}" style="display: inline-block;">
                    <button type="submit" class="HWBtn" style="background: #007bff; color: white; border: none; padding: 6px 12px; border-radius: 5px; cursor: pointer; font-size: 14px;">
                        Szczegóły
                    </button>
                </form>

            </td>
        </tr>
        @endforeach
        </tbody>
    </table>

    @endif
    
    <div style="margin-top:30px; display:flex; justify-content:flex-start;">
        <a href="{{ route('workers.index') }}">
            <button type="button"
                    style="padding:8px 14px; border:1px solid #ddd; background:#f8f9fa; cursor:pointer; color:#000;">
                ← Powrót do listy pracowników
            </button>
        </a>
    </div>
    </div>
</div>
</x-layout>