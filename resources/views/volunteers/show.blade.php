<x-layout>
    <x-slot name="title">Karta Wolontariusza</x-slot>

    @php
        $state = $volunteer->account?->Acc_State ?? 'Pending';
        $stateLower = strtolower($state);
    @endphp

    <div class="container" style="max-width: 900px; margin: 40px auto; padding: 20px; font-family: sans-serif;">
        
        <h2>Pełne informacje: {{ $volunteer->account?->Name ?? 'Brak' }} {{ $volunteer->account?->Last_Name ?? 'Danych' }}</h2>
        
        <!-- Komunikaty przeniesione pod imię i nazwisko -->
        @if(session('generated_password'))
            <div style="padding: 12px 20px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 6px; margin-top: 15px; margin-bottom: 15px; font-size: 15px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                <strong>Hasło dla wolontariusza zostało zresetowane na:</strong>
                <span style="font-family: Consolas, Courier New, monospace; font-size: 16px; font-weight: bold; background: white; padding: 3px 8px; border: 1px solid #dc3545; color: #dc3545; border-radius: 4px; letter-spacing: 1px;">
                    {{ session('generated_password') }}
                </span>
            </div>
        @endif

        @if(session('success'))
            <div style="padding: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px; margin-top: 15px; margin-bottom: 15px; font-size: 14px;">
                {!! session('success') !!}
            </div>
        @endif

        @if(session('error'))
            <div style="padding: 15px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; margin-top: 15px; margin-bottom: 15px; font-size: 14px;">
                {{ session('error') }}
            </div>
        @endif
        
        <div style="background: #fdfdfd; padding: 20px; margin: 20px 0; border-radius: 6px; border: 1px solid #e3e3e3; border-left: 5px solid #007bff;">
            <p style="margin: 6px 0;"><strong>Status konta w systemie:</strong> {{ $state }}</p>
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

        @if($stateLower != 'deleted')
            <div style="background: #f8f9fa; padding: 20px; border-radius: 6px; border: 1px solid #e3e3e3; margin: 20px 0;">
                <h3 style="margin-top: 0; margin-bottom: 15px; color: #333; font-size: 16px;">Panel zarządzania kontem (Pracownik)</h3>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('worker.volunteers.edit', $volunteer->id) }}" style="background: #007bff; color: white; text-decoration: none; padding: 10px 15px; border-radius: 4px; font-weight: bold; font-size: 13px;">Edytuj dane</a>

                    @if($stateLower == 'pending' || $stateLower == 'blocked')
                        <form action="{{ route('worker.volunteers.approve', $volunteer->id) }}" method="POST" style="margin: 0;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" style="background: #28a745; color: white; border: none; padding: 10px 15px; border-radius: 4px; font-weight: bold; font-size: 13px; cursor: pointer;">
                                {{ $stateLower == 'blocked' ? 'Odblokuj konto' : 'Aktywuj konto' }}
                            </button>
                        </form>
                    @endif

                    @if($stateLower == 'active')
                        <form action="{{ route('worker.volunteers.block', $volunteer->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Czy na pewno chcesz zablokować to konto i anulować przyszłe spacery?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" style="background: #ffc107; color: #212529; border: none; padding: 10px 15px; border-radius: 4px; font-weight: bold; font-size: 13px; cursor: pointer;">Zablokuj wolontariusza</button>
                        </form>
                    @endif

                    <form action="{{ route('worker.volunteers.reset-password', $volunteer->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Czy na pewno chcesz wygenerować nowe hasło tymczasowe?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" style="background: #17a2b8; color: white; border: none; padding: 10px 15px; border-radius: 4px; font-weight: bold; font-size: 13px; cursor: pointer;">Resetuj hasło</button>
                    </form>

                    <form action="{{ route('worker.volunteers.destroy', $volunteer->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Czy na pewno chcesz oznaczyć tego wolontariusza jako usuniętego?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: #dc3545; color: white; border: none; padding: 10px 15px; border-radius: 4px; font-weight: bold; font-size: 13px; cursor: pointer;">Usuń profil</button>
                    </form>
                </div>
            </div>
        @endif

        <h3 style="margin-top: 30px; border-bottom: 2px solid #eee; padding-bottom: 10px;">Historia i Grafik Spacerów</h3>
        
        @if($volunteer->schedules->isEmpty())
            <p style="color: #666; font-style: italic; margin-top: 15px;">Ten wolontariusz nie odbył jeszcze żadnego spaceru.</p>
        @else
            <div style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; border-radius: 6px; margin-top: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                <table style="width: 100%; border-collapse: collapse; background: white; font-size: 14px;">
                    <thead>
                        <tr style="background: #f4f4f4; text-align: left; position: sticky; top: 0; z-index: 10; box-shadow: inset 0 -1px 0 #ddd;">
                            <th style="padding: 12px; border-bottom: 1px solid #ddd; border-right: 1px solid #ddd; width: 15%; background: #f4f4f4;">Data & Godzina</th>
                            <th style="padding: 12px; border-bottom: 1px solid #ddd; border-right: 1px solid #ddd; width: 15%; background: #f4f4f4;">Pies</th>
                            <th style="padding: 12px; border-bottom: 1px solid #ddd; border-right: 1px solid #ddd; width: 20%; background: #f4f4f4;">Pracownik nadzorujący</th>
                            <th style="padding: 12px; border-bottom: 1px solid #ddd; width: 50%; background: #f4f4f4;">Ocena & Informacje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($volunteer->schedules as $schedule)
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 12px; border-right: 1px solid #ddd;">{{ $schedule->Date }}</td>
                                <td style="padding: 12px; border-right: 1px solid #ddd;"><strong>{{ $schedule->dog->Name ?? 'Nieznany' }}</strong></td>
                                <td style="padding: 12px; border-right: 1px solid #ddd;">
                                    {{ $schedule->worker?->account?->Name ?? 'Brak' }} {{ $schedule->worker?->account?->Last_Name ?? '' }}
                                </td>
                                <td style="padding: 12px; vertical-align: middle; background: #fafafa;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; gap: 15px; flex-wrap: wrap;">
                                        <div>
                                            @if($schedule->Grade)
                                                <span style="color: #ffc107; font-weight: bold; font-size: 14px;">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        {{ $i <= $schedule->Grade ? '★' : '☆' }}
                                                    @endfor
                                                    <span style="color: #495057; font-weight: normal; margin-left: 5px;">({{ $schedule->Grade }}/5)</span>
                                                </span>
                                            @else
                                                <span style="color: #888; font-style: italic; font-size: 13px;">Brak oceny</span>
                                            @endif
                                        </div>
                                        <a href="#" style="text-decoration: none;">
                                            <span style="background: #007bff; color: white; display: inline-block; padding: 6px 12px; font-size: 13px; border-radius: 4px; font-weight: 500; cursor: pointer;">
                                                Szczegóły spaceru &rarr;
                                            </span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div style="margin-top: 30px;">
            <a href="{{ route('worker.volunteers.index') }}">
                <button style="background: #6c757d; color: white; width: auto; cursor: pointer; padding: 10px 20px; border: none; border-radius: 4px; font-weight: bold;">Powrót do listy</button>
            </a>
        </div>
    </div>
</x-layout>