<?php

namespace Tests\Unit;

use App\DTOs\VolunteerDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class VolunteerDTOTest extends TestCase
{
    /**
     * 1. Test sprawdzający poprawne mapowanie danych z Requestu i hasła na właściwości DTO.
     */
    public function test_dto_maps_correctly_from_request()
    {
        // Tworzymy sztuczny obiekt Request z danymi formularza
        $request = new Request([
            'Name' => 'Jan',
            'Last_Name' => 'Kowalski',
            'Login' => 'jkowal',
            'Email' => 'jan@example.com',
            'Phone_Num' => '123456789',
            'Is_Experienced' => 'on'
        ]);

        $generatedPassword = 'losoweHaslo123';

        // Wywołujemy zaktualizowaną metodę z dwoma parametrami
        $dto = VolunteerDTO::fromRequest($request, $generatedPassword);

        // Sprawdzamy czy właściwości obiektu DTO mają poprawne wartości
        $this->assertEquals('Jan', $dto->name);
        $this->assertEquals('Kowalski', $dto->lastName);
        $this->assertEquals('jkowal', $dto->login);
        $this->assertEquals('jan@example.com', $dto->email);
        $this->assertEquals('123456789', $dto->phoneNum);
        $this->assertEquals('losoweHaslo123', $dto->password);
        $this->assertTrue($dto->isExperienced);
        $this->assertEquals('Pending', $dto->accState); // Domyślnie dla nowego wolontariusza
    }

    /**
     * 2. Test sprawdzający zachowanie domyślnej wartości dla Is_Experienced.
     */
    public function test_dto_uses_default_values_when_checkbox_is_off()
    {
        $request = new Request([
            'Name' => 'Anna',
            'Last_Name' => 'Nowak',
            'Login' => 'anowak',
            'Email' => 'anna@example.com',
            'Phone_Num' => '987654321',
            // brak Is_Experienced oznacza, że checkbox nie został zaznaczony
        ]);

        $dto = VolunteerDTO::fromRequest($request, 'secret');

        $this->assertFalse($dto->isExperienced);
    }

    /**
     * 3. Test sprawdzający czy metoda toAccountArray zwraca odpowiednią strukturę bazy danych i hashuje hasło.
     */
    public function test_dto_converts_to_account_array_and_hashes_password()
    {
        // Tworzymy instancję DTO ręcznie
        $dto = new VolunteerDTO(
            name: 'Marek',
            lastName: 'Zając',
            login: 'mzajac',
            email: 'marek@example.com',
            phoneNum: '555666777',
            password: 'surowe-haslo-startowe',
            isExperienced: true,
            accState: 'Pending'
        );

        // Generujemy tablicę przeznaczoną dla modelu Account
        $accountArray = $dto->toAccountArray();

        // Weryfikujemy obecność kluczy i poprawność danych zgodnych z migracją tabeli 'accounts'
        $this->assertArrayHasKey('Name', $accountArray);
        $this->assertEquals('Marek', $accountArray['Name']);
        $this->assertEquals('Zając', $accountArray['Last_Name']);
        $this->assertEquals('mzajac', $accountArray['Login']);
        $this->assertEquals('marek@example.com', $accountArray['Email']);
        $this->assertEquals('555666777', $accountArray['Phone_Num']);
        $this->assertEquals('Pending', $accountArray['Acc_State']);
        $this->assertEquals(now()->format('Y-m-d'), $accountArray['Creation_Date']);
        
        // Hasło nie może być zapisane otwartym tekstem
        $this->assertNotEquals('surowe-haslo-startowe', $accountArray['Password']);
        
        // Sprawdzamy, czy hasło zostało prawidłowo zahashowane przy użyciu mechanizmów Laravela
        $this->assertTrue(Hash::check('surowe-haslo-startowe', $accountArray['Password']));
    }
}