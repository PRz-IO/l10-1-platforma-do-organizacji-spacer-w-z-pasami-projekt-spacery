<x-layout>
    <style>
        .page-wrapper { max-width: 1000px; margin: 40px auto; padding: 20px; font-family: sans-serif; }
        .history-card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; }
        .alert { padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center; font-weight: bold; }
        .alert-success { background: #d1e7dd; color: #0f5132; }
    </style>

    <div class="page-wrapper">
        <h1>Panel Nadzorcy</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @forelse($supervisorWalks as $walk)
    <div class="history-card" style="display: flex; align-items: center; justify-content: space-between; padding: 15px 20px; margin-bottom: 15px;">
        
        {{-- Kolumna 1: Dane i notatka (Zabiera całą wolną przestrzeń z lewej) --}}
        <div style="flex: 1; text-align: left; padding-right: 20px;">
            <p style="margin: 0;"><strong>Pies:</strong> {{ $walk->dog_name }} | <strong>Data:</strong> {{ $walk->Date }}</p>
            <p style="margin: 5px 0 0 0;"><em>Notatka wolontariusza:</em> {{ $walk->Note ?? 'Brak notatki' }}</p>
        </div>
        
        {{-- Kolumna 2: Wybór oceny (Zablokowana stała szerokość) --}}
        <form action="{{ route('walks.addGrade', $walk->id) }}" method="POST" style="width: 220px; margin: 0; text-align: center;">
            @csrf
            <select name="grade" onchange="this.form.submit()" style="padding: 8px; border-radius: 5px; width: 100%; cursor: pointer;">
                <option value="" disabled selected>Wystaw ocenę (1-5)</option>
                @for($i=1; $i<=5; $i++)
                    <option value="{{ $i }}" {{ $walk->Grade == $i ? 'selected' : '' }}>Ocena: {{ $i }}</option>
                @endfor
            </select>
        </form>
        
        {{-- Kolumna 3: Przycisk Szczegóły (Zablokowana stała szerokość) --}}
        <form method="GET" action="{{ route('walks.details', $walk->id) }}" style="width: 110px; margin: 0; text-align: right; margin-left: 20px;">
            <button type="submit" class="HWBtn" style="background: #007bff; color: white; width: 100%; border: none; padding: 8px; border-radius: 5px; cursor: pointer;">
                Szczegóły
            </button>
        </form>

    </div>
@empty
    <p>Brak spacerów oczekujących na Twoją ocenę.</p>
@endforelse
        
    </div>
</x-layout>