<x-layout>
    <x-slot name="title">Zarządzanie Wolontariuszami</x-slot>

    <div class="container" style="max-width: 800px; margin: 40px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); font-family: sans-serif;">
        <h2 style="margin-bottom: 20px; color: #333;">Panel Zarządzania Wolontariuszami</h2>

        @if(session('success'))
            <div class="alert" style="background: #d4edda; color: #155724; padding: 12px; margin-bottom: 20px; border-radius: 5px; border: 1px solid #c3e6cb; font-size: 14px;">
                {!! session('success') !!}
            </div>
        @endif

        <div style="text-align: right; margin-bottom: 20px;">
            <a href="{{ route('worker.volunteers.create') }}">
                <button style="width: auto; padding: 10px 18px; cursor: pointer; background: #007bff; color: white; border: none; border-radius: 4px; font-weight: bold; font-size: 14px;">+ Dodaj nowego wolontariusza</button>
            </a>
        </div>

        <form action="{{ route('worker.volunteers.index') }}" method="GET" style="margin-bottom: 25px; display: flex; gap: 10px;">
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Wpisz nazwisko, imię lub login wolontariusza..." 
                   style="flex: 1; padding: 10px 15px; border: 1px solid #ced4da; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
            
            <button type="submit" style="width: auto; padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 4px; font-weight: bold; font-size: 14px; cursor: pointer;">
                Szukaj
            </button>

            @if(!empty($search))
                <a href="{{ route('worker.volunteers.index') }}" style="text-decoration: none; box-sizing: border-box;">
                    <span style="display: inline-block; padding: 10px 20px; background: #6c757d; color: white; border-radius: 4px; font-weight: bold; font-size: 14px; text-align: center; cursor: pointer;">
                        Wyczyść
                    </span>
                </a>
            @endif
        </form>

        @if($volunteers->isEmpty())
            <p style="color: #666; text-align: center; margin-top: 30px;">
                {{ !empty($search) ? 'Brak wyników spełniających kryteria wyszukiwania.' : 'Brak zarejestrowanych wolontariuszy w bazie danych.' }}
            </p>
        @else
            <div style="max-height: 550px; overflow-y: auto; padding-right: 5px; border: 1px solid #eee; border-radius: 6px; padding: 10px; background: #fcfcfc;">
                <ul style="padding: 0; margin: 0;">
                    @foreach($volunteers as $volunteer)
                        @php
                            $state = $volunteer->account?->Acc_State ?? 'Pending';
                            $stateLower = strtolower($state);
                        @endphp
                        <li style="margin-bottom: 15px; padding: 20px; border: 1px solid #e0e0e0; border-radius: 6px; list-style: none; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                                
                                <div style="flex: 1; min-width: 250px;">
                                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 5px;">
                                        <strong style="font-size: 18px; color: #222;">
                                            {{ $volunteer->account?->Name ?? 'Brak imienia' }} {{ $volunteer->account?->Last_Name ?? 'Brak nazwiska' }}
                                        </strong>
                                        
                                        <span style="padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; text-transform: uppercase;
                                            background: {{ $stateLower == 'active' ? '#d4edda' : ($stateLower == 'blocked' ? '#f8d7da' : ($stateLower == 'deleted' ? '#e2e3e5' : '#fff3cd')) }};
                                            color: {{ $stateLower == 'active' ? '#155724' : ($stateLower == 'blocked' ? '#721c24' : ($stateLower == 'deleted' ? '#383d41' : '#856404')) }};
                                            border: 1px solid {{ $stateLower == 'active' ? '#c3e6cb' : ($stateLower == 'blocked' ? '#f5c6cb' : ($stateLower == 'deleted' ? '#d6d8db' : '#ffeeba')) }};">
                                            {{ $state }}
                                        </span>
                                    </div>
                                    
                                    <div style="font-size: 13px; color: #555; line-height: 1.6;">
                                        <strong>Login:</strong> {{ $volunteer->account?->Login ?? '---' }} <br>
                                        <strong>Email:</strong> {{ $volunteer->account?->Email ?? '---' }} | <strong>Tel:</strong> {{ $volunteer->account?->Phone_Num ?? '---' }} <br>
                                        <strong>Doświadczenie:</strong> 
                                        <span style="font-weight: bold; color: {{ $volunteer->Is_Experienced ? '#28a745' : '#6c757d' }};">
                                            {{ $volunteer->Is_Experienced ? 'Tak' : 'Nie' }}
                                        </span> <br>
                                        <strong>Ogólna ocena:</strong> 
                                        @if($volunteer->average_rating)
                                            <span style="color: #ffc107; font-weight: bold;">
                                                ★ {{ number_format($volunteer->average_rating, 2) }} / 5
                                            </span>
                                        @else
                                            <span style="color: #888; font-style: italic;">Brak ocen</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
                                    
                                    <a href="{{ route('worker.volunteers.show', $volunteer->id) }}" style="text-decoration: none;">
                                        <span style="background: #007bff; color: white; display: inline-block; padding: 6px 12px; font-size: 13px; border-radius: 4px; font-weight: 500; cursor: pointer;">Profil & Historia </span>
                                    </a>
                                    
                                    @if($stateLower != 'deleted')
                                        <form action="{{ route('worker.volunteers.reset-password', $volunteer->id) }}" method="POST" style="margin: 0; display: inline;" onsubmit="return confirm('Czy na pewno chcesz zresetować hasło temu wolontariuszowi na losowe?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" style="background: #17a2b8; color: white; width: auto; padding: 6px 12px; font-size: 13px; cursor: pointer; border: none; border-radius: 4px; font-weight: 500;">Resetuj hasło</button>
                                        </form>

                                        @if($stateLower == 'pending' || $stateLower == 'blocked')
                                            <form action="{{ route('worker.volunteers.approve', $volunteer->id) }}" method="POST" style="margin: 0; display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" style="background: #28a745; color: white; width: auto; padding: 6px 12px; font-size: 13px; cursor: pointer; border: none; border-radius: 4px; font-weight: 500;">Zatwierdź</button>
                                            </form>
                                        @endif

                                        @if($stateLower == 'active')
                                            <form action="{{ route('worker.volunteers.block', $volunteer->id) }}" method="POST" style="margin: 0; display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" style="background: #ffc107; color: #212529; width: auto; padding: 6px 12px; font-size: 13px; cursor: pointer; border: none; border-radius: 4px; font-weight: 500;">Zablokuj</button>
                                            </form>
                                        @endif

                                        <form action="{{ route('worker.volunteers.destroy', $volunteer->id) }}" method="POST" style="margin: 0; display: inline;" onsubmit="return confirm('Czy na pewno chcesz zmienić status tego wolontariusza na usunięty?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: #dc3545; color: white; width: auto; padding: 6px 12px; font-size: 13px; cursor: pointer; border: none; border-radius: 4px; font-weight: 500;">Usuń</button>
                                        </form>
                                    @endif

                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</x-layout>