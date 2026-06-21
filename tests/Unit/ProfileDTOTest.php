<?php

namespace Tests\Unit;

use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\DTOs\ProfileDTO;

class ProfileDTOTest extends TestCase{
    use RefreshDatabase;
    public function test_dto_constructor_and_getters_work_properly(){
        $dto = new ProfileDTO("Marian","Knyc","MKnyc","2001-09-11","email@email.email","123123123",["key"=>"man"]);
        $this->assertEquals("Marian",$dto->getName(),'Invalid Name');
        $this->assertEquals("Knyc",$dto->getLast_Name(),'Invalid Last Name');
        $this->assertEquals("MKnyc",$dto->getLogin(),'Invalid Login');
        $this->assertEquals("2001-09-11",$dto->getCreation_Date(),'Invalid Creation Date');
        $this->assertEquals("email@email.email",$dto->getEmail(),'Invalid Email');
        $this->assertEquals("123123123",$dto->getPhone_Num(),'Invalid Phone Number');
        $this->assertArrayHasKey("key",$dto->getHistory(),'Invalid History');
    }

    public function test_isempty_method_works_correctly(){
        $dto = new ProfileDTO("Marian","Knyc","MKnyc","2001-09-11","email@email.email","123123123",["key"=>"man"]);
        $this->assertEquals(false,$dto->isEmpty(),"Counts filled history as empty");

        $dto = new ProfileDTO("Marian","Knyc","MKnyc","2001-09-11","email@email.email","123123123",[]);
        $this->assertEquals(true,$dto->isEmpty(),"Counts empty history as filled");
    }

    public function test_new_method_works(){
        $Acc=Account::create(["Name"=>"Marian","Last_Name"=>"Knyc","Login"=>"MKnyc","Password"=>"Smalec",
        "Email"=>"email@email.email","Phone_Num"=>"123123123","Acc_State"=>"Pending"]);
        $Acc->volunteer()->create(["Is_Experienced"=>false]);
        $dto = ProfileDTO::New($Acc->id,"Volunteer");
        $this->assertEquals("Marian",$dto->getName(),'Invalid Name');
        $this->assertEquals("Knyc",$dto->getLast_Name(),'Invalid Last Name');
        $this->assertEquals("MKnyc",$dto->getLogin(),'Invalid Login');
        $this->assertEquals("email@email.email",$dto->getEmail(),'Invalid Email');
        $this->assertEquals("123123123",$dto->getPhone_Num(),'Invalid Phone Number');
        $this->assertEquals([],$dto->getHistory(),'Invalid History');

    }
}
