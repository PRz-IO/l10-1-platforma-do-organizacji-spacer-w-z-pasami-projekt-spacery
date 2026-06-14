<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\DTOs\CurrentUserDTO;

class CurrentUserDTOTest extends TestCase{
    public function test_dto_constructor_and_getters_work_properly(){
        $dto = new CurrentUserDTO(1,"Role",true,"State");
        $this->assertEquals(1,$dto->getId(),'Invalid Id');
        $this->assertEquals("Role",$dto->getRole(),'Invalid Role');
        $this->assertEquals(true,$dto->getParam(),'Invalid Param');
        $this->assertEquals("State",$dto->getAcc_State(),'Invalid Acc_State');
    }
}
