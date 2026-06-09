<x-layout>
    <div class="container" style="max-width: 700px; margin: 40px auto; font-family: sans-serif;">
        
        <a href="javascript:history.back()" style="text-decoration: none; color: #007bff; font-weight: bold; display: inline-block; margin-bottom: 20px;">
            ← Powrót
        </a>

        <div style="border: 1px solid #e0e0e0; border-radius: 10px; padding: 30px; background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            
            <h2 style="margin-top: 0; border-bottom: 2px solid #f0f0f0; padding-bottom: 15px; color: #333;">
                Szczegóły Spaceru #{{ $walk->id }}
            </h2>

            {{-- Sekcja Czasu i Statusu --}}
            <div style="margin-bottom: 25px; background: #f8f9fa; padding: 15px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; font-size: 16px;"><strong>📅 Data:</strong> {{ $walk->Date }}</p>
                    <p style="margin: 5px 0 0 0; font-size: 16px;"><strong>⏰ Godzina:</strong> {{ substr($walk->Time, 0, 5) }}</p>
                </div>
                <div>
                    @if(\Carbon\Carbon::parse($walk->Date . ' ' . $walk->Time, 'Europe/Warsaw')->isFuture())
                        <span style="background: #e3f2fd; color: #0d47a1; padding: 6px 12px; border-radius: 20px; font-weight: bold; font-size: 14px;">Nadchodzący</span>
                    @else
                        <span style="background: #e8f5e9; color: #1b5e20; padding: 6px 12px; border-radius: 20px; font-weight: bold; font-size: 14px;">Zakończony</span>
                    @endif
                </div>
            </div>

            {{-- Sekcja Psa --}}
            <div style="margin-bottom: 25px;">
                <h4 style="margin: 0 0 10px 0; color: #555; text-transform: uppercase; font-size: 12px; letter-spacing: 1px;">Pies</h4>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 24px;">🐶</span>
                    <div>
                        <strong style="font-size: 18px; color: #000;">{{ $dog->Name }}</strong>
                        <p style="margin: 2px 0 0 0; color: #666; font-size: 14px;">Wiek: {{ $dog->Age ?? 'Brak danych' }} lat</p>
                    </div>
                </div>
            </div>

            {{-- Sekcja Notatki Wolontariusza --}}
            <div style="margin-bottom: 25px; border-top: 1px solid #f0f0f0; padding-top: 20px;">
                <h4 style="margin: 0 0 10px 0; color: #555; text-transform: uppercase; font-size: 12px; letter-spacing: 1px;">Raport ze spaceru (Notatka)</h4>
                <div style="background: #fff8e1; border-left: 4px solid #ffb300; padding: 15px; border-radius: 4px; font-style: italic; color: #444;">
                    {{ $walk->Note ?? 'Wolontariusz nie wprowadził jeszcze podsumowania tego spaceru.' }}
                </div>
            </div>

            {{-- Sekcja Oceny Nadzorcy --}}
            <div style="border-top: 1px solid #f0f0f0; padding-top: 20px;">
                <h4 style="margin: 0 0 10px 0; color: #555; text-transform: uppercase; font-size: 12px; letter-spacing: 1px;">Ocena Nadzorcy</h4>
                @if(!is_null($walk->Grade))
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <span style="font-size: 20px; color: #ffc107;">
                            @for($i = 1; $i <= 5; $i++)
                                {{ $i <= $walk->Grade ? '★' : '☆' }}
                            @endfor
                        </span>
                        <strong style="font-size: 16px; margin-left: 5px;">({{ $walk->Grade }}/5)</strong>
                    </div>
                @else
                    <p style="margin: 0; color: #777; font-size: 14px;">Spacer oczekuje na weryfikację i ocenę przez pracownika.</p>
                @endif
            </div>

        </div>
    </div>
</x-layout>