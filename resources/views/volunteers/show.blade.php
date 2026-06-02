<x-layout>
    <x-slot name="title">Karta Wolontariusza</x-slot>

    <div class="container" style="max-width: 800px; margin: 40px auto; padding: 20px; font-family: sans-serif;">
        <h2>Pełne informacje: {{ $volunteer->account?->Name ?? 'Brak' }} {{ $volunteer->account?->Last_Name ?? 'Danych' }}</h2>
        
        <div style="background: #fdfdfd; padding: 20px; margin: 20px 0; border-radius: 6px; border: 1px solid #e3e3e3; border-left: 5px solid #007bff;">
            <p style="margin: 6px 0;"><strong>Status konta w systemie:</strong> {{ $volunteer->account?->Acc_State ?? 'Pending' }}</p>
            <p style="margin: 6px 0;"><strong>Adres Email:</strong> {{ $volunteer->account?->Email ?? '---' }}</p>
            <p style="margin: 6px 0;"><strong>Numer Telefonu:</strong> {{ $volunteer->account?->Phone_Num ?? '---' }}</p>
            <p style="margin: 6px 0;"><strong>Data rejestracji konta:</strong> {{ $volunteer->account?->Date ?? '---' }}</p>
            <p style="margin: 6px 0;"><strong>Kwalifikacje:</strong> {{ $volunteer->Is_Experienced ? 'Doświadczony wolontariusz (Samodzielny)' : 'Brak doświadczenia (Wymaga asysty)' }}</p>
        </div>

        <h3 style="margin-top: 30px; border-bottom: 2px solid #eee; padding-bottom: 10px;">Historia i Grafik Spacerów</h3>
        
        {{-- Pobieramy wpisy z relacji zdefiniowanej w modelu Volunteer --}}
        @if($volunteer->schedules->isEmpty())
            <p style="color: #666; font-style: italic; margin-top: 15px;">Ten wolontariusz nie odbył jeszcze żadnego spaceru.</p>
        @else
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px; background: white;">
                <thead>
                    <tr style="background: #f4f4f4; text-align: left;">
                        <th style="padding: 12px; border: 1px solid #ddd;">Data & Godzina</th>
                        <th style="padding: 12px; border: 1px solid #ddd;">Pies</th>
                        <th style="padding: 12px; border: 1px solid #ddd;">Pracownik nadzorujący</th>
                        <th style="padding: 12px; border: 1px solid #ddd;">Ocena</th>
                        <th style="padding: 12px; border: 1px solid #ddd;">Uwagi</th>
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
                            <td style="padding: 12px; border: 1px solid #ddd;">
                                @if($schedule->Grade)
                                    <span style="color: #ffc107; font-weight: bold;">{{ str_repeat('★', $schedule->Grade) }}</span>
                                    <span style="color: #555;">({{ $schedule->Grade }}/5)</span>
                                @else
                                    <span style="color: #999; font-style: italic;">Nie oceniono</span>
                                @endif
                            </td>
                            <td style="padding: 12px; border: 1px solid #ddd; font-style: italic; color: #444;">
                                "{{ $schedule->Note ?? 'Brak wpisanych uwag po spacerze.' }}"
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