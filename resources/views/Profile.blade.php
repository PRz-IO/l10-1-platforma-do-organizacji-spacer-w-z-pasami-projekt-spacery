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

                <div class="FlexInfo">
                    <strong>Imię i nazwisko:</strong>
                    {{ $ProfileDTO->getName() }} {{ $ProfileDTO->getLast_Name() }}
                </div>

                <div class="FlexInfo">
                    <strong>Email:</strong>
                    {{ $ProfileDTO->getEmail() }}
                </div>

                <div class="FlexInfo">
                    <strong>Telefon:</strong>
                    {{ $ProfileDTO->getPhone_Num() }}
                </div>

                <div class="FlexInfo">
                    <strong>Login:</strong>
                    {{ $ProfileDTO->getLogin() }}
                </div>

                @if (\App\Utilities\CurrUser::getRole()=="Volunteer")
                    <div class="FlexInfo">
                        <strong>Łączna liczba spacerów:</strong>
                        {{ $WCount }}
                    </div>

                    <div class="FlexInfo">
                        <strong>Doświadczenie:</strong>
                        {{ \App\Utilities\CurrUser::getParam() ? 'Tak' : 'Nie' }}
                    </div>
                @else
                    <div class="FlexInfo">
                        <strong>Administrator:</strong>
                        {{ \App\Utilities\CurrUser::getParam() ? 'Tak' : 'Nie' }}
                    </div>
                @endif

                <div class="FlexInfo" style="width: 100%; justify-content: space-around;">
                    <form method="POST" action="{{ route('profile.check') }}">
                        @csrf
                        <input type="hidden" name="type" value="ChPass">
                        <button type="submit">Zmień Hasło</button>
                    </form>
                
                    <form method="POST" action="{{ route('profile.check') }}">
                        @csrf
                        <input type="hidden" name="type" value="DelAcc" >
                        <button type="submit" >Usuń Konto</button>
                    </form>
                </div>
           </div>
        </div> 

            @isset($Err)
                <br><h4 style="justify-content: center; text-align: center;">
                    {{ $Err }}
                </h4><br>
            @endisset

        <div class="ProfInfo" style="text-align: center;">
            <h2 style="margin:0 0 15px 0; text-align:center;">
                Historia Spacerów
            </h2>

            
                @if ($ProfileDTO->isEmpty())
                    <p style="color:#777;">
                        Nie masz jeszcze żadnych odbytych spacerów.
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
                                    {{-- Poprawiona zmienna na wielką literę oraz użycie metody getId() --}}
                                    <form method="GET" action="{{ route('walks.details', $Walk->getId()) }}">
                                        <button type="submit" class="HWBtn">Szczegóły</button>
                                    </form>
                                </div>
                                <div class="HWalkCell">
                                    {{ $Walk->getDate() }} {{ substr($Walk->getTime(), 0, 5) }}
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