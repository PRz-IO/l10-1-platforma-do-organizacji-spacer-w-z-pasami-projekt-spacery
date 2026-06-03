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
            <div class="history-card">
                <div>
                    <p><strong>Pies:</strong> {{ $walk->dog_name }} | <strong>Data:</strong> {{ $walk->Date }}</p>
                    <p><em>Notatka wolontariusza:</em> {{ $walk->Note ?? 'Brak notatki' }}</p>
                </div>
                
                <form action="{{ route('walks.addGrade', $walk->id) }}" method="POST">
                    @csrf
                    <select name="grade" onchange="this.form.submit()" style="padding: 8px; border-radius: 5px;">
                        <option value="" disabled selected>Wystaw ocenę (1-5)</option>
                        @for($i=1; $i<=5; $i++)
                            <option value="{{ $i }}" {{ $walk->Grade == $i ? 'selected' : '' }}>Ocena: {{ $i }}</option>
                        @endfor
                    </select>
                </form>
            </div>
        @empty
            <p>Brak spacerów oczekujących na Twoją ocenę.</p>
        @endforelse
    </div>
</x-layout>