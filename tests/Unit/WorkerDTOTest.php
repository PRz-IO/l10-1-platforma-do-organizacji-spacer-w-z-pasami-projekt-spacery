<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\DTOs\WorkerDTO;

class WorkerDTOTest extends TestCase
{
    public function test_dto_is_created_from_array(): void
    {
        $data = [
            'Name' => 'John',
            'Last_Name' => 'Impact',
            'Login' => 'johnimpact',
            'Password' => 'verypassword',
            'Email' => 'JImpact@czesc.pl',
            'Phone_num' => '123456789',
            'Is_Admin' => true
        ];

        $dto = WorkerDTO::fromArray($data);

        $this->assertEquals('John', $dto->name, 'Actual name not equals expected');
        $this->assertEquals('Impact', $dto->lastName, 'Actual last name not equals expected');
        $this->assertEquals('johnimpact', $dto->login, 'Actual login not equals expected');
        $this->assertEquals('JImpact@czesc.pl', $dto->email, 'Actual email not equals expected');
        $this->assertEquals('123456789', $dto->phone_num, 'Actual phone number not equals expected');
        $this->assertTrue($dto->isAdmin);
    }



    public function test_to_account_array_returns_correct_structure(): void
    {
        $dto = new WorkerDTO(
            name: 'John',
            lastName: 'Impact',
            login: 'johnimpact',
            password: 'verypassword',
            email: 'JImpact@czesc.pl',
            phone_num: '123456789'
        );

        $accountArray = $dto->toAccountArray();

        $this->assertArrayHasKey('Name', $accountArray);
        $this->assertArrayHasKey('Last_Name', $accountArray);
        $this->assertArrayHasKey('Login', $accountArray);
        $this->assertArrayHasKey('Password', $accountArray);
        $this->assertArrayHasKey('Email', $accountArray);
        $this->assertArrayHasKey('Phone_num', $accountArray);
    }


    
    public function test_to_worker_array_returns_correct_structure(): void
    {
        $dto = new WorkerDTO(
            name: 'John',
            lastName: 'Impact',
            login: 'johnimpact',
            password: 'verypassword',
            email: 'JImpact@czesc.pl',
            phone_num: '123456789',
            isAdmin: true
        );

        $workerArray = $dto->toWorkerArray(5);

        $this->assertEquals(5, $workerArray['Account_Id']);
        $this->assertTrue($workerArray['Is_Admin']);
    }
}