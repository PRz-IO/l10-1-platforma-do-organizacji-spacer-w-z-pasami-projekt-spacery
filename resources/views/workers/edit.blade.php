<!-- 
TO DO:
fix everything about it
-->

<x-layout>
    <x-slot name="title">Edytuj pracownika</x-slot>

    <form method="POST" action="{{ route('workers.update', $worker->getKey()) }}">
        @csrf
        @method('PUT')

        <input name="Name" value="{{ $worker->account->Name }}">
        <input name="Last_Name" value="{{ $worker->account->Last_Name }}">

        <button type="submit">Zapisz</button>
    </form>
</x-layout>