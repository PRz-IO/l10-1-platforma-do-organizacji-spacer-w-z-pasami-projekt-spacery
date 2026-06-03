<!-- 
TO DO:
Take styles from main .css file?
Creating, updating and deleting workers should be available only to admin
-->

<x-layout>
    <x-slot name="title">Zarządzanie Pracownikami</x-slot>

    <div class="container">
        <h2>Panel Zarządzania Pracownikami</h2>

        @if(session('success'))
            <div style="background:#d4edda; padding:10px; margin-bottom:15px;">
                {{ session('success') }}
            </div>
        @endif

        <div style="text-align:right; margin-bottom:20px;">
            <a href="{{ route('workers.create') }}">
                <button>+ Dodaj pracownika</button>
            </a>
        </div>

        @if($workers->isEmpty())
            <p>Brak pracowników w bazie.</p>
        @else
            <ul style="padding:0;">
                @foreach($workers as $worker)
                    <li style="list-style:none; border:1px solid #ddd; padding:15px; margin-bottom:10px;">
                        
                        <div style="display:flex; justify-content:space-between; align-items:center;">

                            {{-- LEFT SIDE --}}
                            <div>
                                <strong>
                                    {{ $worker->account?->Name ?? 'Brak' }}
                                    {{ $worker->account?->Last_Name ?? '' }}
                                </strong>

                                <br>

                                <small>
                                    Login: {{ $worker->account?->Login ?? '---' }}
                                    | Email: {{ $worker->account?->Email ?? '---' }}
                                </small>

                                <br>

                                <span>
                                    Admin:
                                    <strong>{{ $worker->Is_Admin ? 'Tak' : 'Nie' }}</strong>
                                </span>
                            </div>

                            {{-- RIGHT SIDE --}}
                            <div style="display:flex; gap:8px;">

                                <a href="{{ route('workers.show', $worker->id) }}">
                                    <button>Podgląd</button>
                                </a>

                                <a href="{{ route('workers.edit', $worker->id) }}">
                                    <button>Edytuj</button>
                                </a>

                                <form action="{{ route('workers.destroy', $worker->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Usunąć pracownika?')">

                                    @csrf
                                    @method('DELETE')

                                    <button style="background:red; color:white;">
                                        Usuń
                                    </button>
                                </form>

                            </div>
                        </div>

                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-layout>