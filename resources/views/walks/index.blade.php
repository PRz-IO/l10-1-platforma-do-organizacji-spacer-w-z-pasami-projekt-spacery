<x-layout>
    <style>
        /* Rozciągamy kontener na większą szerokość */
        .page-wrapper { width: 100%; max-width: 1600px; margin: 0 auto; padding: 20px; font-family: sans-serif; display: flex; gap: 40px; align-items: flex-start; }
        
        /* Lewa kolumna zajmuje teraz 70% miejsca */
        .main-content { flex: 7; }
        
        /* Siatka psów teraz zadziała, bo ma więcej miejsca */
        .dogs-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 25px; }
        
        /* Prawa kolumna zajmuje 30% miejsca */
        .sidebar { width: 30%; min-width: 350px; background: #f8f9fa; border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px; }
        
        /* Poprawiamy wygląd pola tekstowego notatki */
        .note-input { width: 100%; min-height: 100px; padding: 12px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; font-size: 14px; margin-bottom: 10px; resize: none; }
        
        /* Reszta stylów pozostaje bez zmian... */
        .dog-card { background: white; border: 1px solid #eaeaea; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .dog-img { width: 100%; height: 220px; object-fit: cover; }
        .dog-info { padding: 20px; }
        .btn-profile { display: block; text-align: center; padding: 10px; background: #0d6efd; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 15px; }
        .history-card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
    </style>

    <div class="page-wrapper">
        
        <div class="main-content">
            <div class="page-header">
                <h1>Nasi Podopieczni</h1>
                <p>Wybierz psa, aby poznać go bliżej i umówić się na spacer.</p>
            </div>

            <div class="dogs-grid">
                @foreach($dogs as $dog)
                    <div class="dog-card">
                        @if($dog->Photo)
                            <img src="{{ asset($dog->Photo) }}" alt="{{ $dog->Name }}" class="dog-img">
                        @else
                            <div class="dog-img-placeholder">Brak zdjęcia</div>
                        @endif
                        
                        <div class="dog-info">
                            <h2 class="dog-name">{{ $dog->Name }}</h2>
                            <div class="dog-details">
                                <p><strong>Wiek:</strong> {{ $dog->Age }} lat</p>
                                <p><strong>Stan:</strong> {{ $dog->State }}</p>
                            </div>
                            <a href="{{ url('/psy/' . $dog->id) }}" class="btn-profile">Zobacz profil</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @if (\App\Utilities\CurrUser::isLogged() && \App\Utilities\CurrUser::getRole() == "Volunteer")
            <div class="sidebar">
                <h2>Moje Spacery</h2>
                
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(count($myWalks) > 0)
                    @php
                        $today = date('Y-m-d');
                    @endphp

                    @foreach($myWalks as $walk)
                        <div class="history-card">
                            <p class="h-date">📅 {{ $walk->Date }} o {{ substr($walk->Time, 0, 5) }}</p>
                            <p class="h-dog">🐶 Pies: <strong>{{ $walk->dog_name }}</strong></p>

                            @if($walk->Date > $today)
                                <span class="status-badge status-future">Nadchodzący</span>
    
                            <form action="{{ route('walks.cancel', $walk->id) }}" method="POST" style="margin-top: 5px;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn-note" 
                                        style="background: #dc3545;" 
                                        onclick="return confirm('Czy na pewno chcesz anulować ten spacer?')">
                                    Anuluj spacer
                                </button>
                            </form>
                            @else
                                <span class="status-badge status-past">Zakończony</span>
                                
                                @if(empty($walk->Note))
                                    <form action="{{ route('walks.addNote', $walk->id) }}" method="POST" style="margin-top: 10px;">
                                        @csrf
                                        <textarea name="note" class="note-input" placeholder="Jak zachowywał się pies?" required></textarea>
                                        <button type="submit" class="btn-note">Zapisz notatkę</button>
                                    </form>
                                @else
                                    <div class="existing-note" style="margin-top: 10px;">
                                        <strong>Twoja notatka:</strong><br>
                                        {{ $walk->Note }}
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                @else
                    <p style="color: #777; font-size: 14px;">Nie masz jeszcze żadnych zapisanych spacerów.</p>
                @endif
            </div>
        @endif

    </div>
</x-layout>