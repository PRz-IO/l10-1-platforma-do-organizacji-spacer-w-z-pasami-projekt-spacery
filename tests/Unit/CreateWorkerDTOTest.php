<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\DTOs\CreateWorkerDTO;

class CreateWorkerDTOTest extends TestCase
{
    public function test_dto_is_created_from_array(): void
    {
        $data = [
            'Name' => 'John',
            'Last_Name' => 'Impact',
            'Login' => 'johnimpact',
            'Password' => 'verypassword',
            'Email' => 'JImpact@czesc.pl',
            'Phone_Num' => '123456789',
            'Is_Admin' => true
        ];

        $dto = CreateWorkerDTO::fromArray($data);

        $this->assertEquals('John', $dto->name, 'Actual name not equals expected');
        $this->assertEquals('Impact', $dto->lastName, 'Actual last name not equals expected');
        $this->assertEquals('johnimpact', $dto->login, 'Actual login not equals expected');
        $this->assertEquals('JImpact@czesc.pl', $dto->email, 'Actual email not equals expected');
        $this->assertEquals('123456789', $dto->phone_num, 'Actual phone number not equals expected');
        $this->assertTrue($dto->isAdmin);
    }



    public function test_to_account_array_returns_correct_structure(): void
    {
        $dto = new CreateWorkerDTO(
            name: 'John',
            lastName: 'Impact',
            login: 'johnimpact',
            password: 'verypassword',
            email: 'JImpact@czesc.pl',
            phone_num: '123456789'
        );

        $accountArray = $dto->toAccountArray();

        $this->assertArrayHasKey('Name', $accountArray, 'Array doesnt contain "Name" as key');
        $this->assertArrayHasKey('Last_Name', $accountArray, 'Array doesnt contain "Last_Name" as key');
        $this->assertArrayHasKey('Login', $accountArray, 'Array doesnt contain "Login" as key');
        $this->assertArrayHasKey('Password', $accountArray, 'Array doesnt contain "Password" as key');
        $this->assertArrayHasKey('Email', $accountArray, 'Array doesnt contain "Email" as key');
        $this->assertArrayHasKey('Phone_Num', $accountArray, 'Array doesnt contain "Phone_num" as key');

        $this->assertEquals('John', $accountArray['Name'], 'Actual name not equals expected');
        $this->assertEquals('Impact', $accountArray['Last_Name'], 'Actual last name not equals expected');
        $this->assertEquals('johnimpact', $accountArray['Login'], 'Actual login not equals expected');
        $this->assertEquals('JImpact@czesc.pl', $accountArray['Email'], 'Actual email not equals expected');
        $this->assertEquals('123456789', $accountArray['Phone_Num'], 'Actual phone number not equals expected');
    }


    
    public function test_to_worker_array_returns_correct_structure(): void
    {
        $dto = new CreateWorkerDTO(
            name: 'John',
            lastName: 'Impact',
            login: 'johnimpact',
            password: 'verypassword',
            email: 'JImpact@czesc.pl',
            phone_num: '123456789',
            isAdmin: true
        );

        $workerArray = $dto->toWorkerArray(5);

        $this->assertArrayHasKey('account_id', $workerArray, 'Array doesnt contain "account_id" as key');
        $this->assertArrayHasKey('Is_Admin', $workerArray, 'Array doesnt contain "Is_Admin" as key');

        $this->assertEquals(5, $workerArray['account_id']);
        $this->assertTrue($workerArray['Is_Admin']);
    }
}