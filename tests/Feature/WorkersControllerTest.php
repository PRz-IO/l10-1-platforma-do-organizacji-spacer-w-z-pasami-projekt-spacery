<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Worker;
use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WorkersControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_workers(): void
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

        Worker::create([
            'account_id' => $account->getKey(),
            'Is_Admin' => true
        ]);

        $response = $this->from(route('workers.index'))->withSession([
            'Id' => $account->id,
            'Role' => 'Worker',
            'Param' => true,
            'AccState' => 'Active',
            'IsLogged' => true,
        ])->get(route('workers.index'));

        $response->assertOk();
        $response->assertSee('John');
        $response->assertSee('Impact');
        $response->assertSee('johnimpact');
    }



    public function test_store_creates_worker_and_account(): void
    {
        $data = [
            'Name' => 'John',
            'Last_Name' => 'Impact',
            'Login' => 'johnimpact',
            'Email' => 'JImpact@czesc.pl',
            'Phone_Num' => '123456789',
            'Is_Admin' => true,
        ];

        $response = $this->from(route('workers.index'))->withSession([
            'Id' => 1,
            'Role' => 'Worker',
            'Param' => true,
            'AccState' => 'Active',
            'IsLogged' => true,
        ])->post(route('workers.store'), $data);

        $response->assertRedirect(route('workers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('accounts', [
            'Login' => 'johnimpact',
            'Name' => 'John',
            'Last_Name' => 'Impact',
            'Email' => 'JImpact@czesc.pl',
        ]);

        $account = Account::where('Login', 'johnimpact')->first();

        $this->assertDatabaseHas('workers', [
            'account_id' => $account->id,
            'Is_Admin' => true,
        ]);
    }

    public function test_show_returns_404_for_missing_worker(): void
    {
        $response = $this->withSession([
            'Id' => 1,
            'Role' => 'Worker',
            'Param' => true,
            'AccState' => 'Active',
            'IsLogged' => true,
        ])->get(route('workers.show', 999));

        $response->assertNotFound();
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
            'account_id' => $account->getKey(),
            'Is_Admin' => false
        ]);

        $response = $this->from(route('workers.index'))->withSession([
            'Id' => 1,
            'Role' => 'Worker',
            'Param' => true,
            'AccState' => 'Active',
            'IsLogged' => true,
        ])->put(route('workers.update', $worker->getKey()), [
            'Name' => 'John',
            'Last_Name' => 'Rail',
            'Email' => 'JRail@czesc.pl',
            'Phone_Num' => '987654321',
            'Is_Admin' => true
        ]);

        $response->assertRedirect(route('workers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('Accounts', [
            'id' => $account->getKey(),
            'Name' => 'John',
            'Last_Name' => 'Rail',
            'Email' => 'JRail@czesc.pl',
            'Phone_Num' => '987654321',
        ]);

        $this->assertDatabaseHas('Workers', [
            'id' => $worker->getKey(),
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
            'account_id' => $account->getKey(),
            'Is_Admin' => false
        ]);

        $response = $this->from(route('workers.index'))->withSession([
            'Id' => 1,
            'Role' => 'Worker',
            'Param' => true,
            'AccState' => 'Active',
            'IsLogged' => true,
        ])->delete(route('workers.destroy', $worker));

        $response->assertRedirect(route('workers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'Acc_State' => 'Deleted'
        ]);

        $this->assertDatabaseHas('workers', [
            'id' => $worker->id
        ]);
    }

    public function test_block_blocks_worker(): void
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
            'account_id' => $account->getKey(),
            'Is_Admin' => false
        ]);

        $response = $this->from(route('workers.index'))->withSession([
            'Id' => 1,
            'Role' => 'Worker',
            'Param' => true,
            'AccState' => 'Active',
            'IsLogged' => true,
        ])->patch(route('workers.block', $worker));

        $response->assertRedirect(route('workers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'Acc_State' => 'Blocked'
        ]);

        $this->assertDatabaseHas('workers', [
            'id' => $worker->id
        ]);
    }

    public function test_unblock_unblocks_worker(): void
    {
        $account = Account::create([
            'Name' => 'John',
            'Last_Name' => 'Impact',
            'Login' => 'johnimpact',
            'Password' => bcrypt('verypassword'),
            'Email' => 'JImpact@czesc.pl',
            'Phone_Num' => '123456789',
            'Acc_State' => 'Blocked'
        ]);

        $worker = Worker::create([
            'account_id' => $account->getKey(),
            'Is_Admin' => false
        ]);

        $response = $this->from(route('workers.index'))->withSession([
            'Id' => 1,
            'Role' => 'Worker',
            'Param' => true,
            'AccState' => 'Active',
            'IsLogged' => true,
        ])->patch(route('workers.unblock', $worker));

        $response->assertRedirect(route('workers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'Acc_State' => 'Active'
        ]);

        $this->assertDatabaseHas('workers', [
            'id' => $worker->id
        ]);
    }

    public function test_approve_approves_worker(): void
    {
        $account = Account::create([
            'Name' => 'John',
            'Last_Name' => 'Impact',
            'Login' => 'johnimpact',
            'Password' => bcrypt('verypassword'),
            'Email' => 'JImpact@czesc.pl',
            'Phone_Num' => '123456789',
            'Acc_State' => 'Pending'
        ]);

        $worker = Worker::create([
            'account_id' => $account->getKey(),
            'Is_Admin' => false
        ]);

        $response = $this->from(route('workers.index'))->withSession([
            'Id' => 1,
            'Role' => 'Worker',
            'Param' => true,
            'AccState' => 'Active',
            'IsLogged' => true,
        ])->patch(route('workers.approve', $worker));

        $response->assertRedirect(route('workers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'Acc_State' => 'Active'
        ]);

        $this->assertDatabaseHas('workers', [
            'id' => $worker->id
        ]);
    }

    public function test_resetPassword_resets_password_for_worker(): void
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
            'account_id' => $account->getKey(),
            'Is_Admin' => false
        ]);

        $oldPassword = $account->Password;

        $response = $this->from(route('workers.index'))->withSession([
            'Id' => 1,
            'Role' => 'Worker',
            'Param' => true,
            'AccState' => 'Active',
            'IsLogged' => true,
        ])->post(route('workers.reset-password', $worker));

        $account->refresh();

        $response->assertRedirect(route('workers.index'));
        $response->assertSessionHas('generated_password');

        $this->assertNotEquals($oldPassword, $account->Password);
    }
}