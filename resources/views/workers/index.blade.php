<!-- 
TO DO:
Take styles from main .css file?
Creating, updating and deleting workers should be available only to admin
-->

<x-layout>
    <x-slot name="title">Zarządzanie Pracownikami</x-slot>
    


    <div class="container">
        <h2>Panel Zarządzania Pracownikami</h2>

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

        @if(\App\Utilities\CurrUser::IsLogged() && 
            \App\Utilities\CurrUser::getRole()=="Worker" &&
            \App\Utilities\CurrUser::getAcc_State() !="Pending" &&
            \App\Utilities\CurrUser::getParam() ==True
        )
            <div style="text-align:right; margin-bottom:20px;">
                <a href="{{ route('workers.create') }}">
                    <button>+ Dodaj pracownika</button>
                </a>
            </div>
        @endif

        @if($workers->isEmpty())
            <p>Brak pracowników w bazie.</p>
        @else
            <ul style="padding:0;">
                @foreach($workers as $worker)
                <!-- redefining this like this is dumb but will work for now -->
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

                <li style="list-style:none; border:1px solid #ddd; padding:15px; margin-bottom:10px; border-radius:6px;">

                <div>

                    <strong style="font-size:18px;">
                        {{ $worker->account?->Name ?? 'Brak' }}
                        {{ $worker->account?->Last_Name ?? '' }}
                    </strong>
                    
                    @if(\App\Utilities\CurrUser::IsLogged() && 
                        \App\Utilities\CurrUser::getRole()=="Worker" &&
                        \App\Utilities\CurrUser::getAcc_State() !="Pending"
                    )
                    <span style="
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
                    @endif

                    <br><br>

                    <small>
                        {{-- @if (\App\Utilities\CurrUser::IsLogged()) --}}
                            

                        @if(\App\Utilities\CurrUser::IsLogged() && 
                            \App\Utilities\CurrUser::getRole()=="Worker" &&
                            \App\Utilities\CurrUser::getAcc_State() !="Pending" &&
                            \App\Utilities\CurrUser::getParam() ==True
                        )
                        
                            Login: {{ $worker->account?->Login ?? '---' }}
                            <br>
                        @endif

                        Email: {{ $worker->account?->Email ?? '---' }}

                    </small>

                    <br><br>
                    
                    @if(\App\Utilities\CurrUser::IsLogged() && 
                        \App\Utilities\CurrUser::getRole()=="Worker" &&
                        \App\Utilities\CurrUser::getAcc_State() !="Pending"
                    )
                    <span>
                        Admin:
                        <strong>{{ $worker->Is_Admin ? 'Tak' : 'Nie' }}</strong>
                    </span>
                    @endif

                </div>

                <div style="
                    margin-top:15px;
                    display:flex;
                    flex-wrap:wrap;
                    gap:8px;
                ">
                        @if(\App\Utilities\CurrUser::IsLogged() && 
                            \App\Utilities\CurrUser::getRole()=="Worker" &&
                            \App\Utilities\CurrUser::getAcc_State() !="Pending"
                        )
                        <a href="{{ route('workers.show', $worker->id) }}">
                            <button type="button">
                                Profil
                            </button>
                        </a>

                        @endif

                        @if(\App\Utilities\CurrUser::IsLogged() && 
                            \App\Utilities\CurrUser::getRole()=="Worker" &&
                            \App\Utilities\CurrUser::getAcc_State() !="Pending" &&
                            \App\Utilities\CurrUser::getParam() ==True
                        )

                        <a href="{{ route('workers.edit', $worker->id) }}">
                            <button type="button">
                                Edytuj
                            </button>
                        </a>

                        <form method="POST"
                            action="{{ route('workers.reset-password', $worker->id) }}">
                            @csrf

                            <button type="submit"
                                    style="background:#ffc107;">
                                Reset hasła
                            </button>
                        </form>

                        @if($worker->account?->Acc_State === 'Active')

                            <form method="POST"
                                action="{{ route('workers.block', $worker->id) }}">
                                @csrf
                                @method('PATCH')

                                <button type="submit"
                                        style="background:#fd7e14; color:white;">
                                    Zablokuj
                                </button>
                            </form>

                        @elseif($worker->account?->Acc_State === 'Blocked')

                            <form method="POST"
                                action="{{ route('workers.unblock', $worker->id) }}">
                                @csrf
                                @method('PATCH')

                                <button type="submit"
                                        style="background:#28a745; color:white;">
                                    Odblokuj
                                </button>
                            </form>

                        @elseif($worker->account?->Acc_State === 'Pending')

                            <form method="POST"
                                action="{{ route('workers.approve', $worker->id) }}">
                                @csrf
                                @method('PATCH')

                                <button type="submit"
                                        style="background:#28a745; color:white;">
                                    Zatwierdź
                                </button>
                            </form>

                        @endif

                        <form action="{{ route('workers.destroy', $worker->id) }}"
                            method="POST"
                            onsubmit="return confirm('Oznaczyć pracownika jako usuniętego?')">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    style="background:red; color:white;">
                                Usuń
                            </button>
                        </form>
                        
                    @endif

                </div>

                </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-layout>