<x-layout>
    <x-slot:title>Panel Użytkownika - Bark & Boop</x-slot:title>

    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .welcome-hero {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            border: 1px solid #e3e3e0;
        }

        .welcome-hero h1 {
            margin: 0 0 10px 0;
            color: #1b1b18;
            font-size: 28px;
        }

        .welcome-hero p {
            margin: 0;
            color: #706f6c;
        }

        .grid-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .action-card {
            background: white;
            border: 1px solid #e3e3e0;
            border-radius: 10px;
            padding: 24px;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.01);
        }

        .action-card:hover {
            transform: translateY(-3px);
            border-color: #007bff;
            box-shadow: 0 4px 12px rgba(0,123,255,0.1);
        }

        .action-card h3 {
            margin: 0 0 10px 0;
            font-size: 18px;
            color: #1b1b18;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .action-card p {
            margin: 0;
            color: #706f6c;
            font-size: 14px;
            line-height: 1.5;
        }

        .stats-compact {
            display: flex;
            gap: 20px;
            background: white;
            border: 1px solid #e3e3e0;
            border-radius: 10px;
            padding: 20px;
        }

        .stat-item {
            flex: 1;
            text-align: center;
            border-right: 1px solid #e3e3e0;
        }

        .stat-item:last-child {
            border-right: none;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }

        .stat-label {
            font-size: 12px;
            color: #706f6c;
            text-transform: uppercase;
            margin-top: 4px;
            letter-spacing: 0.5px;
        }
    </style>

    <div class="dashboard-container">
        
        {{-- Sekcja powitalna --}}
        <div class="welcome-hero">
            <h1>Cześć, dobrze Cię widzieć! 👋</h1>
            <p>Zarządzaj swoimi aktywnościami, przeglądaj harmonogramy spacerów i pomagaj naszym podopiecznym.</p>
        </div>

        {{-- Szybkie akcje (Skróty na kafelkach) --}}
        <h2 style="font-size: 20px; margin-bottom: 15px; color: #1b1b18;">Szybkie akcje</h2>
        <div class="grid-actions">
            
            <a href="{{ route('walks.index') }}" class="action-card">
                <h3>🦮 Grafik spacerów</h3>
                <p>Przejrzyj zaplanowane wyjścia, zgłoś swoją dyspozycyjność lub zarezerwuj termin.</p>
            </a>

            <a href="{{ route('dogs.index') }}" class="action-card">
                <h3>🐕 Nasi podopieczni</h3>
                <p>Zobacz profile psów oczekujących na spacery. Poznaj ich charaktery.</p>
            </a>

            <a href="{{ route('profile.index') }}" class="action-card">
                <h3>👤 Twój profil</h3>
                <p>Zaktualizuj swoje dane kontaktowe, sprawdź status konta i historię swoich spacerów.</p>
            </a>

            {{-- Dodatkowy kafelek widoczny tylko dla Pracownika --}}
            @if (\App\Utilities\CurrUser::getRole() == "Worker")
                <a href="{{ route('worker.volunteers.index') }}" class="action-card" style="border-color: #ffeeba; background: #fffdf5;">
                    <h3>🛠️ Panel Pracownika</h3>
                    <p>Zarządzaj weryfikacją wolontariuszy, edytuj dane psów i kontroluj działanie schroniska.</p>
                </a>
            @endif

        </div>

        {{-- Podsumowanie statystyk --}}
        <h2 style="font-size: 20px; margin-bottom: 15px; color: #1b1b18;">Aktualny stan schroniska</h2>
        <div class="stats-compact">
            <div class="stat-item">
                <div class="stat-number">{{ $dogsCount }}</div>
                <div class="stat-label">Psów pod opieką</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $walksCount }}</div>
                <div class="stat-label">Zgłoszonych spacerów</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $volunteersCount }}</div>
                <div class="stat-label">Aktywnych wolontariuszy</div>
            </div>
        </div>

    </div>
</x-layout>