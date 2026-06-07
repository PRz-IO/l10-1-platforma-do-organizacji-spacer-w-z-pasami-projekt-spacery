<x-layout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    @endpush
    <div class="container" style="display:block; text-align:left;">

        <h1 style="margin-bottom:20px; text-align:center;">
            Profil
        </h1>


        <div class="ProfInfo">

            <h2 style="margin:0 0 15px 0; text-align:center;">
                Dane
            </h2>

            <div style="display:flex;flex-direction:column;gap:8px;align-items:flex-start;text-align:left;">

                <div style="display:flex;gap:8px;align-items:flex-start;">
                    <strong>Imię i nazwisko:</strong>
                    {{ $ProfileDTO->getName() }} {{ $ProfileDTO->getLast_Name() }}
                </div>

                <div style="display:flex;gap:8px;align-items:flex-start;">
                    <strong>Email:</strong>
                    {{ $ProfileDTO->getEmail() }}
                </div>

                <div style="display:flex;gap:8px;align-items:flex-start;">
                    <strong>Telefon:</strong>
                    {{ $ProfileDTO->getPhone_Num() }}
                </div>

                <div style="display:flex;gap:8px;align-items:flex-start;">
                    <strong>Login:</strong>
                    {{ $ProfileDTO->getLogin() }}
                </div>

                <div style="display:flex;gap:8px;align-items:flex-start;">
                    <strong>Doświadczenie:</strong>
                    {{ \App\Utilities\CurrUser::getParam() ? 'Tak' : 'Nie' }}
                </div>
           </div>
        </div> 
        <div class="ProfInfo" style="text-align: center;">
            <h2 style="margin:0 0 15px 0; text-align:center;">
                Historia Spacerów
            </h2>

            
                @if ($ProfileDTO->isEmpty())
                    <p style="color:#777;">
                        Du hast keine spaceren.
                    </p>
                @else
                    <div class="ProfInfo" style="overflow-y: scroll; max-height: 40vh;">
                        @foreach ($ProfileDTO->getHistory() as $Walk)
                            <div class="HWalk">
                                <div class="HWalkCell">
                                    <strong>{{ $Walk->getDName() }}</strong>
                                </div>
                                <div class="HWalkCell">
                                    @if (is_null($Walk->getGrade()))
                                        Brak oceny
                                    @else
                                        <span style="color: #ffc107; font-weight: bold;">
                                        @for($i = 1; $i <= 5; $i++)
                                            {{ $i <= $Walk->getGrade() ? '★' : '☆' }}
                                        @endfor
                                        </span>
                                    @endif
                                </div>
                                <div class="HWalkCell" style="grid-row: span 2;">
                                    <form method="" action="#">
                                        <button type="submit" class="HWBtn">Szczegóły</button>
                                    </form>
                                </div>
                                <div class="HWalkCell">
                                    {{ $Walk->getDate() }} {{ $Walk->getTime() }}
                                </div>
                                <div class="HWalkCell">
                                    {{ $Walk->getName() }} {{ $Walk->getLast_Name() }}
                                </div>
                                
                            </div>
                        @endforeach
                    </div>
                    
                @endif
        </div>
    </div>
</x-layout>
