<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Volunteer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class VolunteerManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. TEST TWORZENIA WOLONTARIUSZA (Metoda: store - Ścieżka sukcesu)
     */
    public function test_worker_can_create_volunteer_successfully()
    {
        $payload = [
            'Name' => 'Jan',
            'Last_Name' => 'Kowalski',
            'Login' => 'jkowalski',
            'Email' => 'jan@example.com',
            'Phone_Num' => '123456789',
            'Is_Experienced' => true,
        ];

        $response = $this->post(route('worker.volunteers.store'), $payload);

        $response->assertRedirect(route('worker.volunteers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('accounts', [
            'Name' => 'Jan',
            'Login' => 'jkowalski',
            'Email' => 'jan@example.com',
            'Phone_Num' => '123456789',
            'Acc_State' => 'Pending',
        ]);

        $this->assertDatabaseHas('volunteers', [
            'Is_Experienced' => 1,
        ]);
    }

    /**
     * 2. TEST TWORZENIA WOLONTARIUSZA (Metoda: store - Błąd walidacji unikalności)
     */
    public function test_volunteer_creation_fails_when_phone_is_not_unique()
    {
        Account::create([
            'Name' => 'Anna',
            'Last_Name' => 'Nowak',
            'Login' => 'anowak',
            'Password' => 'secret',
            'Email' => 'anna@example.com',
            'Phone_Num' => '987654321',
            'Acc_State' => 'Active',
            'Creation_Date' => now()->format('Y-m-d'),
        ]);

        $payload = [
            'Name' => 'Piotr',
            'Last_Name' => 'Zieliński',
            'Login' => 'pzielinski',
            'Email' => 'piotr@example.com',
            'Phone_Num' => '987654321', // Duplikat numeru telefonu
        ];

        $response = $this->post(route('worker.volunteers.store'), $payload);

        $response->assertSessionHasErrors(['Phone_Num']);
        $this->assertDatabaseMissing('accounts', [
            'Login' => 'pzielinski',
        ]);
    }

    /**
     * 3. TEST EDYCJI DANYCH WOLONTARIUSZA (Metoda: update)
     */
    public function test_worker_can_update_volunteer_details()
    {
        $account = Account::create([
            'Name' => 'Stary', 'Last_Name' => 'Kowalski', 'Login' => 'jkowal',
            'Password' => 'secret', 'Email' => 'stary@example.com', 'Phone_Num' => '111111111',
            'Acc_State' => 'Active', 'Creation_Date' => now()->format('Y-m-d')
        ]);
        $volunteer = Volunteer::create(['account_id' => $account->id, 'Is_Experienced' => false]);

        $payload = [
            'Name' => 'Nowy',
            'Last_Name' => 'Nowak',
            'Email' => 'nowy@example.com',
            'Phone_Num' => '222222222',
            'Is_Experienced' => true
        ];

        $response = $this->put(route('worker.volunteers.update', $volunteer->id), $payload);

        $response->assertRedirect(route('worker.volunteers.show', $volunteer->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'Name' => 'Nowy',
            'Phone_Num' => '222222222'
        ]);
    }

    /**
     * 4. TEST ZATWIERDZANIA KONTA (Metoda: approve)
     */
    public function test_worker_can_approve_volunteer_account()
    {
        $account = Account::create([
            'Name' => 'Tomasz', 'Last_Name' => 'Kot', 'Login' => 'tkot',
            'Password' => 'secret', 'Email' => 'tomasz@kot.pl', 'Phone_Num' => '555444333',
            'Acc_State' => 'Pending', 'Creation_Date' => now()->format('Y-m-d')
        ]);
        $volunteer = Volunteer::create(['account_id' => $account->id]);

        // Zmieniono na PATCH zgodnie z web.php
        $response = $this->from(route('worker.volunteers.index'))
                         ->patch(route('worker.volunteers.approve', $volunteer->id));

        $response->assertRedirect(route('worker.volunteers.index'));
        
        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'Acc_State' => 'Active'
        ]);
    }

    /**
     * 5. TEST BLOKOWANIA WOLONTARIUSZA (Metoda: block)
     */
    public function test_worker_can_block_volunteer()
    {
        $account = Account::create([
            'Name' => 'Jan', 'Last_Name' => 'Kowalski', 'Login' => 'jkowal',
            'Password' => 'secret', 'Email' => 'jan@kowal.pl', 'Phone_Num' => '111222333',
            'Acc_State' => 'Active', 'Creation_Date' => now()->format('Y-m-d')
        ]);
        $volunteer = Volunteer::create(['account_id' => $account->id]);

        // Zmieniono na PATCH zgodnie z web.php
        $response = $this->from(route('worker.volunteers.show', $volunteer->id))
                         ->patch(route('worker.volunteers.block', $volunteer->id));

        $response->assertRedirect(route('worker.volunteers.show', $volunteer->id));

        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'Acc_State' => 'Blocked'
        ]);
    }

    /**
     * 6. TEST USUWANIA WOLONTARIUSZA (Metoda: destroy)
     */
    public function test_worker_can_delete_volunteer_virtually()
    {
        $account = Account::create([
            'Name' => 'Marek', 'Last_Name' => 'Zajac', 'Login' => 'mzajac',
            'Password' => 'secret', 'Email' => 'marek@zajac.pl', 'Phone_Num' => '777888999',
            'Acc_State' => 'Active', 'Creation_Date' => now()->format('Y-m-d')
        ]);
        $volunteer = Volunteer::create(['account_id' => $account->id]);

        $response = $this->delete(route('worker.volunteers.destroy', $volunteer->id));

        $response->assertRedirect(route('worker.volunteers.index'));

        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'Acc_State' => 'Deleted'
        ]);
    }

    /**
     * 7. TEST RESETOWANIA HASŁA (Metoda: resetPassword)
     */
    public function test_worker_can_reset_volunteer_password()
    {
        $oldPasswordHash = Hash::make('StareHaslo123');
        
        $account = Account::create([
            'Name' => 'Adam', 'Last_Name' => 'Nowak', 'Login' => 'anowak',
            'Password' => $oldPasswordHash, 'Email' => 'adam@nowak.pl', 'Phone_Num' => '555666777',
            'Acc_State' => 'Active', 'Creation_Date' => now()->format('Y-m-d')
        ]);
        $volunteer = Volunteer::create(['account_id' => $account->id]);

        // Poprawiono metodę na PATCH i nazwę trasy na 'volunteers.reset-password'
        $response = $this->from(route('worker.volunteers.show', $volunteer->id))
                         ->patch(route('worker.volunteers.reset-password', $volunteer->id));

        $response->assertRedirect(route('worker.volunteers.show', $volunteer->id));
        $response->assertSessionHas('success');

        $account->refresh();

        // Upewniamy się, że hasło zostało zaktualizowane (jest inne niż stary hash)
        $this->assertNotEquals($oldPasswordHash, $account->Password);
    }
}