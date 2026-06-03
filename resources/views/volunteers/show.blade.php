<x-layout>
    <x-slot name="title">Karta Wolontariusza</x-slot>

    <div class="container" style="max-width: 900px; margin: 40px auto; padding: 20px; font-family: sans-serif;">
        
        @if(session('generated_password'))
            <div style="padding: 20px; background-color: #fff5f5; color: #dc3545; border: 2px dashed #dc3545; border-radius: 6px; margin-bottom: 20px; font-size: 15px; text-align: center;">
                <strong style="display: block; margin-bottom: 8px; font-size: 16px;">⚠️ Wygenerowano nowe hasło tymczasowe!</strong>
                Użytkownik musi zmienić je przy pierwszym logowaniu. Hasło: 
                <span style="font-family: Consolas, Courier New, monospace; font-size: 18px; font-weight: bold; background: white; padding: 4px 10px; border: 1px solid #dc3545; border-radius: 4px; margin-left: 5px; display: inline-block; letter-spacing: 2px;">
                    {{ session('generated_password') }}
                </span>
            </div>
        @endif

        @if(session('success'))
            <div style="padding: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 20px; font-size: 14px;">
                {!! session('success') !!}
            </div>
        @endif

        @if(session('error'))
            <div style="padding: 15px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 20px; font-size: 14px;">
                {{ session('error') }}
            </div>
        @endif

        <h2>Pełne informacje: {{ $volunteer->account?->Name ?? 'Brak' }} {{ $volunteer->account?->Last_Name ?? 'Danych' }}</h2>
        
        <div style="background: #fdfdfd; padding: 20px; margin: 20px 0; border-radius: 6px; border: 1px solid #e3e3e3; border-left: 5px solid #007bff;">
            <p style="margin: 6px 0;"><strong>Status konta w systemie:</strong> {{ $volunteer->account?->Acc_State ?? 'Pending' }}</p>
            <p style="margin: 6px 0;"><strong>Adres Email:</strong> {{ $volunteer->account?->Email ?? '---' }}</p>
            <p style="margin: 6px 0;"><strong>Numer Telefonu:</strong> {{ $volunteer->account?->Phone_Num ?? '---' }}</p>
            <p style="margin: 6px 0;"><strong>Data rejestracji konta:</strong> {{ $volunteer->account?->Creation_Date ?? '---' }}</p>
            <p style="margin: 6px 0;"><strong>Kwalifikacje:</strong> {{ $volunteer->Is_Experienced ? 'Doświadczony wolontariusz (Samodzielny)' : 'Brak doświadczenia (Wymaga asysty)' }}</p>
            <p style="margin: 6px 0;"><strong>Ogólna ocena wolontariusza:</strong> 
                @if($volunteer->average_rating)
                    <span style="color: #ffc107; font-weight: bold; background: #fff9e6; padding: 2px 6px; border-radius: 4px; border: 1px solid #ffeeba;">
                        ★ {{ number_format($volunteer->average_rating, 2) }} / 5
                    </span>
                @else
                    <span style="color: #888; font-style: italic;">Brak ocen</span>
                @endif
            </p>
        </div>

        <div style="background: #f8f9fa; padding: 20px; border-radius: 6px; border: 1px solid #e3e3e3; margin: 20px 0;">
            <h3 style="margin-top: 0; margin-bottom: 15px; color: #333; font-size: 16px;">Panel zarządzania kontem (Pracownik)</h3>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('worker.volunteers.edit', $volunteer->id) }}" style="background: #007bff; color: white; text-decoration: none; padding: 10px 15px; border-radius: 4px; font-weight: bold; font-size: 13px;">Edytuj dane</a>

                @if($volunteer->account?->Acc_State == 'Pending')
                    <form action="{{ route('worker.volunteers.approve', $volunteer->id) }}" method="POST" style="margin: 0;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" style="background: #28a745; color: white; border: none; padding: 10px 15px; border-radius: 4px; font-weight: bold; font-size: 13px; cursor: pointer;">Aktywuj konto</button>
                    </form>
                @endif

                @if($volunteer->account?->Acc_State != 'Blocked' && $volunteer->account?->Acc_State != 'Deleted')
                    <form action="{{ route('worker.volunteers.block', $volunteer->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Czy na pewno chcesz zablokować to konto i anulować przyszłe spacery?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" style="background: #dc3545; color: white; border: none; padding: 10px 15px; border-radius: 4px; font-weight: bold; font-size: 13px; cursor: pointer;">Zablokuj wolontariusza</button>
                    </form>
                @endif

                <form action="{{ route('worker.volunteers.reset-password', $volunteer->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Czy na pewno chcesz wygenerować nowe hasło tymczasowe?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" style="background: #ffc107; color: #212529; border: none; padding: 10px 15px; border-radius: 4px; font-weight: bold; font-size: 13px; cursor: pointer;">Resetuj hasło</button>
                </form>

                @if($volunteer->account?->Acc_State != 'Deleted')
                    <form action="{{ route('worker.volunteers.destroy', $volunteer->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Czy na pewno chcesz oznaczyć tego wolontariusza jako usuniętego?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: #6c757d; color: white; border: none; padding: 10px 15px; border-radius: 4px; font-weight: bold; font-size: 13px; cursor: pointer;">Usuń profil</button>
                    </form>
                @endif
            </div>
        </div>

        <h3 style="margin-top: 30px; border-bottom: 2px solid #eee; padding-bottom: 10px;">Historia i Grafik Spacerów</h3>
        
        @if($volunteer->schedules->isEmpty())
            <p style="color: #666; font-style: italic; margin-top: 15px;">Ten wolontariusz nie odbył jeszcze żadnego spaceru.</p>
        @else
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px; background: white; font-size: 14px;">
                <thead>
                    <tr style="background: #f4f4f4; text-align: left;">
                        <th style="padding: 12px; border: 1px solid #ddd; width: 15%;">Data & Godzina</th>
                        <th style="padding: 12px; border: 1px solid #ddd; width: 15%;">Pies</th>
                        <th style="padding: 12px; border: 1px solid #ddd; width: 20%;">Pracownik nadzorujący</th>
                        <th style="padding: 12px; border: 1px solid #ddd; width: 50%;">Ocena i uwagi po spacerze (Panel Pracownika)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($volunteer->schedules as $schedule)
                        <tr>
                            <td style="padding: 12px; border: 1px solid #ddd;">{{ $schedule->Date }}</td>
                            <td style="padding: 12px; border: 1px solid #ddd;"><strong>{{ $schedule->dog->Name ?? 'Nieznany' }}</strong></td>
                            <td style="padding: 12px; border: 1px solid #ddd;">
                                {{ $schedule->worker?->account?->Name ?? 'Brak' }} {{ $schedule->worker?->account?->Last_Name ?? '' }}
                            </td>
                            <td style="padding: 12px; border: 1px solid #ddd; background: #fafafa;">
                                <form action="{{ route('worker.schedules.rate', $schedule->id) }}" method="POST" style="margin: 0; display: flex; flex-direction: column; gap: 8px;">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <select name="Grade" style="padding: 5px; border: 1px solid #ccc; border-radius: 4px; background: white; font-size: 13px;" required>
                                            <option value="">Wybierz ocenę...</option>
                                            <option value="5" {{ $schedule->Grade == 5 ? 'selected' : '' }}>★ 5 - Bardzo dobrze</option>
                                            <option value="4" {{ $schedule->Grade == 4 ? 'selected' : '' }}>★ 4 - Dobrze</option>
                                            <option value="3" {{ $schedule->Grade == 3 ? 'selected' : '' }}>★ 3 - Przeciętnie</option>
                                            <option value="2" {{ $schedule->Grade == 2 ? 'selected' : '' }}>★ 2 - Słabo</option>
                                            <option value="1" {{ $schedule->Grade == 1 ? 'selected' : '' }}>★ 1 - Bardzo słabo</option>
                                        </select>

                                        @if($schedule->Grade)
                                            <span style="color: #ffc107; font-weight: bold; font-size: 13px;">
                                                Aktualna: {{ str_repeat('★', $schedule->Grade) }} ({{ $schedule->Grade }}/5)
                                            </span>
                                        @endif
                                    </div>

                                    <div style="display: flex; gap: 8px;">
                                        <input type="text" name="Note" value="{{ $schedule->Note }}" placeholder="Wpisz uwagi ze spaceru..." style="flex: 1; padding: 6px 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 13px;">
                                        <button type="submit" style="background: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 4px; font-weight: bold; font-size: 13px; cursor: pointer;">
                                            Zapisz
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div style="margin-top: 30px;">
            <a href="{{ route('worker.volunteers.index') }}">
                <button style="background: #6c757d; color: white; width: auto; cursor: pointer; padding: 10px 20px; border: none; border-radius: 4px; font-weight: bold;">Powrót do listy</button>
            </a>
        </div>
    </div>
</x-layout>