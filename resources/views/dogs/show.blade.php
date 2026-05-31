<x-layout>
    <div class="container">
        <h1>{{ $dog->Name }}</h1>

        <p>Wiek: {{ $dog->Age }}</p>
        <p>Zachowanie: {{ $dog->Behaviour }}</p>
        <p>Stan: {{ $dog->State }}</p>

        <img src="{{ $dog->Photo }}" alt="dog photo">
   


<a href="/dogs/{{ $dog->id }}/edit">Edytuj psa</a>
<br>
<a href="/dogs/{{ $dog->id }}/walks">Historia spacerów</a>
</div>
</x-layout>
