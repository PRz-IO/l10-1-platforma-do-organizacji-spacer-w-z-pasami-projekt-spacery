<x-layout>
    <style>
        .dog-profile-wrapper { max-width: 800px; margin: 40px auto; font-family: sans-serif; text-align: center; }
        .header-container { display: flex; align-items: center; justify-content: center; gap: 20px; margin-bottom: 20px; flex-wrap: wrap; }
        .btn-fav { background: white; border: 2px solid #ff4d4d; color: #ff4d4d; padding: 8px 16px; border-radius: 20px; cursor: pointer; font-weight: bold; font-size: 15px; transition: 0.2s; }
        .btn-fav.active { background: #ff4d4d; color: white; }
        .btn-fav:hover { filter: brightness(0.9); }
        .dog-photo { width: 250px; height: 250px; object-fit: cover; border-radius: 8px; margin-bottom: 20px; border: 2px solid #eee; }
        .dog-photo-placeholder { width: 250px; height: 250px; background: #f0f0f0; margin: 0 auto 20px auto; display: flex; align-items: center; justify-content: center; color: #888; border-radius: 8px; }
        .info-grid { display: flex; justify-content: center; gap: 20px; margin-bottom: 40px; }
        .info-card { background: #fafafa; border: 1px solid #ddd; padding: 20px; border-radius: 8px; width: 45%; text-align: left; box-sizing: border-box; }
        .info-card h3 { margin-top: 0; border-bottom: 1px solid #ccc; padding-bottom: 10px; color: #333; }
        .walk-form-section { border-top: 2px solid #eee; padding-top: 30px; max-width: 400px; margin: 0 auto; text-align: left; }
        .walk-form-section h2 { text-align: center; color: #333; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; color: #555; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; font-size: 15px; }
        .btn-submit { width: 100%; padding: 12px; background: #0d6efd; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; margin-top: 10px; transition: background 0.2s; }
        .btn-submit:hover { background: #0b5ed7; }
        .alert { padding: 12px; border-radius: 5px; margin-top: 15px; text-align: center; font-weight: bold; }
        .alert-success { background: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; }
        .alert-error { background: #f8d7da; color: #842029; border: 1px solid #f5c2c7; }
        .alert-info { background: #cff4fc; color: #055160; border: 1px solid #b6effb; }
    </style>

    <div class="dog-profile-wrapper">
        <div class="header-container">
            <h1 style="color: #222; margin: 0;">Profil Psa: {{ $dog->Name }}</h1>
            
            @if (\App\Utilities\CurrUser::isLogged() && \App\Utilities\CurrUser::getRole() == "Volunteer")
                <form action="{{ route('walks.favorite', $dog->id) }}" method="POST" style="margin: 0;">
                    @csrf
                    @if($isFavorite)
                        <button type="submit" class="btn-fav active">❤️ Usuń z ulubionych</button>
                    @else
                        <button type="submit" class="btn-fav">🤍 Dodaj do ulubionych</button>
                    @endif
                </form>
            @endif
        </div>
        
        @if($dog->Photo)
            <img src="{{ asset($dog->Photo) }}" alt="Zdjęcie psa {{ $dog->Name }}" class="dog-photo">
        @else
            <div class="dog-photo-placeholder">Brak zdjęcia</div>
        @endif

        <div class="info-grid">
            <div class="info-card">
                <h3>Podstawowe informacje</h3>
                <p><strong>Wiek:</strong> {{ $dog->Age }} lat</p>
                <p><strong>Stan:</strong> {{ \App\Utilities\DogState::label($dog->State) }}</p>
            </div>
            
            <div class="info-card" style="background: #f0f7ff; border-color: #cce3ff;">
                <h3 style="color: #004085; border-bottom-color: #b8daff;">Zachowanie i uwagi</h3>
                <p style="color: #004085; margin: 0;">{{ $dog->Behaviour ?? 'Brak specjalnych uwag. Pies jest przyjazny.' }}</p>
            </div>
        </div>

        <div class="walk-form-section">
            <h2>Zarezerwuj spacer</h2>
            
            @if (\App\Utilities\CurrUser::isLogged() && \App\Utilities\CurrUser::getRole() == "Volunteer")
                <form action="{{ route('walks.reserve', $dog->id) }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="walk_date">Wybierz datę spaceru</label>
                        <input type="date" id="walk_date" name="walk_date" required min="{{ date('Y-m-d') }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="walk_time">Wybierz godzinę</label>
                        <select id="walk_time" name="walk_time" required class="form-control">
                            <option value="" disabled selected>-- Wybierz datę najpierw --</option>
                            @php
                                $times = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'];
                            @endphp
                            @foreach($times as $time)
                                <option value="{{ $time }}:00" class="time-option">{{ $time }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn-submit">Potwierdź rezerwację</button>
                </form>
            @else
                <div class="alert alert-info">
                    Tylko zalogowani wolontariusze mogą rezerwować spacery z tym psem.
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
        </div>
    </div>

    <script>
        document.getElementById('walk_date')?.addEventListener('change', function() {
            let date = this.value;
            let dogId = {{ $dog->id }};
            let timeSelect = document.getElementById('walk_time');
            
            if (!timeSelect) return; 
            
            timeSelect.options[0].text = "-- Wybierz godzinę --";
            
            // Pobieranie aktualnej daty i godziny
            let now = new Date();
            // Wyciągamy dzisiejszą datę w formacie YYYY-MM-DD
            let todayStr = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0') + '-' + String(now.getDate()).padStart(2, '0');
            let currentHour = now.getHours();

            // Krok 1: Włącz wszystkie opcje na starcie, ale zablokuj te z przeszłości, jeśli data to "dzisiaj"
            document.querySelectorAll('.time-option').forEach(option => {
                option.disabled = false;
                let optionTime = option.value.substring(0, 5); 
                let optionHour = parseInt(option.value.substring(0, 2));

                if (date === todayStr && optionHour <= currentHour) {
                    option.disabled = true;
                    option.text = optionTime + ' (Czas minął)';
                } else {
                    option.text = optionTime;
                }
            });

            // Krok 2: Pobierz zajęte godziny z bazy i zablokuj je dodatkowo
            if(date) {
                fetch(`/psy/${dogId}/zajete-godziny?date=${date}`)
                    .then(response => response.json())
                    .then(bookedTimes => {
                        bookedTimes.forEach(time => {
                            let option = document.querySelector(`option[value="${time}"]`); 
                            if(option) {
                                option.disabled = true;
                                option.text = option.value.substring(0, 5) + ' (Zajęte)';
                            }
                        });
                    })
                    .catch(error => console.error('Błąd:', error));
            }
        });
    </script>
</x-layout>