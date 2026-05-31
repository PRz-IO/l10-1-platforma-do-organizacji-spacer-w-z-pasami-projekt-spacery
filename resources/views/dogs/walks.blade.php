<x-layout>
    <div class="container">
        <h1>Historia spacerów</h1>

        @foreach($walks as $walk)
            <div style="margin-bottom: 10px;">
                <p><strong>Data:</strong> {{ $walk->Date }}</p>
                <p><strong>Godzina:</strong> {{ $walk->Time }}</p>
                <p><strong>Notatka:</strong> {{ $walk->Note }}</p>
                <p><strong>Ocena:</strong> {{ $walk->Grade }}</p>
            </div>
        @endforeach
    </div>
</x-layout>