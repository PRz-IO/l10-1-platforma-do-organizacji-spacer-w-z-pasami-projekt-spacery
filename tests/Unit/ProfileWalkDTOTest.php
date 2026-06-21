<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\DTOs\ProfileWalkDTO;

class ProfileWalkDTOTest extends TestCase{
    public function test_dto_constructor_and_getters_work_properly(){
        $dto = new ProfileWalkDTO(1,"Dog","Date","Time",NULL,"Name","Surname");
        $this->assertEquals(1,$dto->getId(),'Invalid Id');
        $this->assertEquals("Dog",$dto->getDName(),'Invalid Dog Name');
        $this->assertEquals("Date",$dto->getDate(),'Invalid Date');
        $this->assertEquals("Time",$dto->getTime(),'Invalid Time');
        $this->assertEquals("Name",$dto->getName(),'Invalid Name');
        $this->assertEquals("Surname",$dto->getLast_Name(),'Invalid Last Name');
        
        $this->assertNull($dto->getGrade(),"Empty date is not null");

        $dto = new ProfileWalkDTO(1,"Dog","Date","Time",5,"Name","Surname");
        $this->assertEquals(5,$dto->getGrade(),'Grade is correctly not null but wrong value');
    }
}