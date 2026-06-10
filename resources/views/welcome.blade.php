<x-layout>
    <x-slot name="title">Strona Główna - Bark & Boop Spacery</x-slot>

    {{-- HERO SECTION --}}
    <div style="background: linear-gradient(135deg, #fdfdfc 0%, #e3e3e0 100%); padding: 80px 20px; text-align: center; border-bottom: 1px solid #e3e3e0;">
        <div style="max-width: 800px; margin: 0 auto; font-family: sans-serif;">
            <h1 style="font-size: 42px; color: #1b1b18; margin-bottom: 20px; font-weight: bold; letter-spacing: -1px;">
                Podaruj radość naszym czworonogom 🐾
            </h1>
            <p style="font-size: 18px; color: #706f6c; line-height: 1.6; margin-bottom: 35px; max-width: 600px; margin-left: auto; margin-right: auto;">
                Witaj w systemie koordynacji spacerów. Zostań wolontariuszem, rezerwuj terminy i spędzaj niezapomniane chwile z psiakami.
            </p>
            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                @auth
                    <a href="{{ route('worker.volunteers.index') }}" style="background: #007bff; color: white; padding: 14px 28px; text-decoration: none; border-radius: 6px; font-weight: bold; box-shadow: 0 4px 6px rgba(0,123,255,0.15);">
                        Zarządzaj Wolontariuszami
                    </a>
                @else
                    <a href="/login" style="background: #007bff; color: white; padding: 14px 28px; text-decoration: none; border-radius: 6px; font-weight: bold; box-shadow: 0 4px 6px rgba(0,123,255,0.15);">
                        Zaloguj się
                    </a>
                    <a href="/signup" style="background: white; color: #1b1b18; padding: 14px 28px; text-decoration: none; border-radius: 6px; font-weight: bold; border: 1px solid #e3e3e0; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                        Zarejestruj się
                    </a>
                @endauth
            </div>
        </div>
    </div>

    {{-- STATS SECTION --}}
    <div style="max-width: 1100px; margin: 0 auto; padding: 60px 20px; font-family: sans-serif;">
        <div style="text-align: center; margin-bottom: 60px;">
            <h2 style="font-size: 28px; color: #1b1b18; margin-bottom: 10px;">Nasz wspólny wpływ</h2>
            <p style="color: #706f6c; margin-bottom: 40px;">Każda minuta na wybiegu to krok bliżej do nowego domu.</p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div style="background: #fdfdfc; padding: 30px; border-radius: 8px; border: 1px solid #e3e3e0; border-top: 4px solid #007bff;">
                    <div style="font-size: 36px; font-weight: bold; color: #1b1b18; margin-bottom: 5px;">{{ $dogsCount }}</div>
                    <div style="color: #706f6c; font-weight: 500;">Psiaków pod opieką</div>
                </div>
                <div style="background: #fdfdfc; padding: 30px; border-radius: 8px; border: 1px solid #e3e3e0; border-top: 4px solid #28a745;">
                    <div style="font-size: 36px; font-weight: bold; color: #1b1b18; margin-bottom: 5px;">{{ number_format($walksCount) }}</div>
                    <div style="color: #706f6c; font-weight: 500;">Odbytych spacerów</div>
                </div>
                <div style="background: #fdfdfc; padding: 30px; border-radius: 8px; border: 1px solid #e3e3e0; border-top: 4px solid #ffc107;">
                    <div style="font-size: 36px; font-weight: bold; color: #1b1b18; margin-bottom: 5px;">{{ $volunteersCount }}</div>
                    <div style="color: #706f6c; font-weight: 500;">Aktywnych wolontariuszy</div>
                </div>
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid #e3e3e0; margin: 60px 0;">

        {{-- HOW IT WORKS --}}
        <div style="text-align: center; margin-bottom: 50px;">
            <h2 style="font-size: 28px; color: #1b1b18; margin-bottom: 10px;">Jak zacząć pomagać?</h2>
            <p style="color: #706f6c;">To tylko 4 proste kroki dzielące Cię od merdającego ogona</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 30px; text-align: center;">
            <div>
                <div style="width: 50px; height: 50px; background: #007bff; color: white; font-size: 20px; font-weight: bold; display: flex; align-items: center; justify-content: center; border-radius: 50%; margin: 0 auto 20px auto;">1</div>
                <h3 style="font-size: 18px; margin-bottom: 10px; color: #1b1b18;">Zarejestruj się</h3>
                <p style="font-size: 14px; color: #706f6c; line-height: 1.5;">Załóż konto wolontariusza wypełniając podstawowe dane kontaktowe.</p>
            </div>
            <div>
                <div style="width: 50px; height: 50px; background: #706f6c; color: white; font-size: 20px; font-weight: bold; display: flex; align-items: center; justify-content: center; border-radius: 50%; margin: 0 auto 20px auto;">2</div>
                <h3 style="font-size: 18px; margin-bottom: 10px; color: #1b1b18;">Zatwierdzenie</h3>
                <p style="font-size: 14px; color: #706f6c; line-height: 1.5;">Nasi pracownicy zweryfikują Twoje zgłoszenie i aktywują profil w systemie.</p>
            </div>
            <div>
                <div style="width: 50px; height: 50px; background: #706f6c; color: white; font-size: 20px; font-weight: bold; display: flex; align-items: center; justify-content: center; border-radius: 50%; margin: 0 auto 20px auto;">3</div>
                <h3 style="font-size: 18px; margin-bottom: 10px; color: #1b1b18;">Wybierz spacer</h3>
                <p style="font-size: 14px; color: #706f6c; line-height: 1.5;">Przeglądaj wolne grafiki psów, dopasuj datę i zarezerwuj swój termin.</p>
            </div>
            <div>
                <div style="width: 50px; height: 50px; background: #28a745; color: white; font-size: 20px; font-weight: bold; display: flex; align-items: center; justify-content: center; border-radius: 50%; margin: 0 auto 20px auto;">4</div>
                <h3 style="font-size: 18px; margin-bottom: 10px; color: #1b1b18;">Ruszaj w drogę!</h3>
                <p style="font-size: 14px; color: #706f6c; line-height: 1.5;">Odbierz psa ze schroniska pod nadzorem opiekuna i ciesz się wspólnym czasem.</p>
            </div>
        </div>
    </div>
</x-layout>