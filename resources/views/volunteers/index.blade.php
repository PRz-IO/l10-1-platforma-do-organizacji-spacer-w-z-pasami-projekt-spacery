<x-layout>
    <x-slot name="title">Zarządzanie Wolontariuszami</x-slot>

    <div class="container">
        <h2>Panel Zarządzania Wolontariuszami</h2>

        @if(session('success'))
            <div class="alert" style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #c3e6cb;">
                {{ session('success') }}
            </div>
        @endif

        <div style="text-align: right; margin-bottom: 20px;">
            <a href="{{ route('worker.volunteers.create') }}">
                <button style="width: auto; padding: 10px 15px; cursor: pointer;">+ Dodaj nowego wolontariusza</button>
            </a>
        </div>

        @if($volunteers->isEmpty())
            <p>Brak zarejestrowanych wolontariuszy w bazie danych.</p>
        @else
            <ul style="padding: 0;">
                @foreach($volunteers as $volunteer)
                    <li style="margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; list-style: none; background: #fff;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <strong style="font-size: 18px;">
                                    {{ $volunteer->account?->Name ?? 'Brak' }} {{ $volunteer->account?->Last_Name ?? 'danych konta' }}
                                </strong>
                                <span style="margin-left: 10px; padding: 3px 8px; border-radius: 3px; font-size: 12px; font-weight: bold; 
                                    background: {{ ($volunteer->account?->Acc_State ?? '') == 'Active' ? '#d4edda' : (($volunteer->account?->Acc_State ?? '') == 'Blocked' ? '#f8d7da' : '#fff3cd') }};
                                    color: {{ ($volunteer->account?->Acc_State ?? '') == 'Active' ? '#155724' : (($volunteer->account?->Acc_State ?? '') == 'Blocked' ? '#721c24' : '#856404') }};">
                                    {{ $volunteer->account?->Acc_State ?? 'Nieaktywne' }}
                                </span>
                                <br>
                                <small style="color: #666;">
                                    Login: {{ $volunteer->account?->Login ?? '---' }} | Email: {{ $volunteer->account?->Email ?? '---' }} | Tel: {{ $volunteer->account?->Phone_Num ?? '---' }}
                                </small>
                                <br>
                                <span style="font-size: 14px;">Doświadczenie: <strong>{{ $volunteer->Is_Experienced ? 'Tak' : 'Nie' }}</strong></span>
                            </div>
                            
                            <div style="display: flex; gap: 8px;">
                                {{-- Używamy getKey(), aby uniknąć błędów z nazwą pola ID --}}
                                <a href="{{ route('worker.volunteers.show', $volunteer->getKey()) }}" style="text-decoration: none;">
                                    <button style="background: #007bff; color: white; width: auto; padding: 5px 10px; font-size: 13px; cursor: pointer;">Historia & Profil</button>
                                </a>
                                <a href="{{ route('worker.volunteers.edit', $volunteer->getKey()) }}" style="text-decoration: none;">
                                    <button style="background: #6c757d; color: white; width: auto; padding: 5px 10px; font-size: 13px; cursor: pointer;">Edytuj</button>
                                </a>

                                @if(($volunteer->account?->Acc_State ?? '') == 'Pending')
                                    <form action="{{ route('worker.volunteers.approve', $volunteer->getKey()) }}" method="POST" style="display:inline;">
                                        @csrf @method('PATCH')
                                        <button type="submit" style="background: #28a745; color: white; width: auto; padding: 5px 10px; font-size: 13px; cursor: pointer;">Zatwierdź</button>
                                    </form>
                                @endif

                                @if(($volunteer->account?->Acc_State ?? '') == 'Active')
                                    <form action="{{ route('worker.volunteers.block', $volunteer->getKey()) }}" method="POST" style="display:inline;">
                                        @csrf @method('PATCH')
                                        <button type="submit" style="background: #ffc107; color: #212529; width: auto; padding: 5px 10px; font-size: 13px; cursor: pointer;">Zablokuj</button>
                                    </form>
                                @endif

                                <form action="{{ route('worker.volunteers.destroy', $volunteer->getKey()) }}" method="POST" style="display:inline;" onsubmit="return confirm('Czy na pewno chcesz bezpowrotnie usunąć tego wolontariusza?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background: #dc3545; color: white; width: auto; padding: 5px 10px; font-size: 13px; cursor: pointer;">Usuń</button>
                                </form>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-layout>