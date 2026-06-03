<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Worker;
use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;


// TO DO:
// Only update test doesn't work
// Other tests are suited for JSON controller responses but controller returns view
// These tests actually work but need rewriting to show success
class WorkersControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_workers(): void
    {
        $this->withoutExceptionHandling();

        $account = Account::create([
            'Name' => 'John',
            'Last_Name' => 'Impact',
            'Login' => 'johnimpact',
            'Password' => bcrypt('verypassword'),
            'Email' => 'JImpact@czesc.pl',
            'Phone_Num' => '123456789',
            'Acc_State' => 'Active'
        ]);

        Worker::create([
            'Account_Id' => $account->getKey(),
            'Is_Admin' => true
        ]);

        $response = $this->get(route('workers.index'));

        $response->assertStatus(200);
    }



    public function test_store_creates_worker_and_account(): void
    {
        $data = [
            'Name' => 'John',
            'Last_Name' => 'Impact',
            'Login' => 'johnimpact',
            'Password' => bcrypt('verypassword'),
            'Email' => 'JImpact@czesc.pl',
            'Phone_Num' => '123456789',
            'Acc_State' => 'Active',
            'Is_Admin' => true
        ];

        $response = $this->post(route('workers.store'), $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('Accounts', [
            'Login' => 'johnimpact'
        ]);

        $this->assertDatabaseHas('Workers', [
            'Is_Admin' => true
        ]);
    }


    public function test_show_returns_404_for_missing_worker(): void
    {
        $response = $this->get('/workers/999');

        $response->assertStatus(404);
    }

    public function test_update_worker(): void
    {
        $account = Account::create([
            'Name' => 'John',
            'Last_Name' => 'Impact',
            'Login' => 'johnimpact',
            'Password' => bcrypt('verypassword'),
            'Email' => 'JImpact@czesc.pl',
            'Phone_Num' => '123456789',
            'Acc_State' => 'Active'
        ]);

        $worker = Worker::create([
            'Account_Id' => $account->getKey(),
            'Is_Admin' => false
        ]);

        // dd($worker->getKey(), $worker->id);

        // dd($worker->Account_Id);
        // dd($account->Id);

        // dd($worker->Account_Id, $worker->toArray());


        $response = $this->put(route('workers.update', $worker->getKey()), [
            'Name' => 'John',
            'Last_Name' => 'Rail',
            'Login' => 'johnrail',
            'Password' => bcrypt('newpassword'),
            'Email' => 'JRail@czesc.pl',
            'Phone_Num' => '987654321',
            'Acc_State' => 'Banned',
            'Is_Admin' => true
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('Accounts', [
            'Id' => $account->getKey(),
            'Name' => 'John',
            'Last_Name' => 'Rail',
            'Login' => 'johnrail',
            'Password' => bcrypt('newpassword'),
            'Email' => 'JRail@czesc.pl',
            'Phone_Num' => '987654321',
            'Acc_State' => 'Banned',
        ]);

        $this->assertDatabaseHas('Workers', [
            'Id' => $worker->getKey(),
            'Is_Admin' => true
        ]);
    }

    public function test_delete_removes_worker(): void
    {
        $account = Account::create([
            'Name' => 'John',
            'Last_Name' => 'Impact',
            'Login' => 'johnimpact',
            'Password' => bcrypt('verypassword'),
            'Email' => 'JImpact@czesc.pl',
            'Phone_Num' => '123456789',
            'Acc_State' => 'Active'
        ]);

        $worker = Worker::create([
            'Account_Id' => $account->getKey(),
            'Is_Admin' => false
        ]);

        $response = $this->delete(route('workers.destroy', $worker));

        $response->assertStatus(200);
    }
}