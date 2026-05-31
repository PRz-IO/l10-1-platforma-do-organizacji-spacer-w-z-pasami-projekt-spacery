<x-layout>
    <div class="container">
        <h1>Lista psów</h1>

        @foreach($dogs as $dog)
            <div style="margin-bottom: 10px;">
                <a href="/dogs/{{ $dog->id }}">
                    {{ $dog->Name }}
                </a>
            </div>
        @endforeach
    </div>
</x-layout>