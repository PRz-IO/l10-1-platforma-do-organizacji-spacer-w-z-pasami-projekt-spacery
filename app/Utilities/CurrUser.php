<?php

namespace App\Utilities;

use App\DTOs\CurrentUserDTO;

class CurrUser
{
    private static int $Id;
    private static string $Role;
    private static bool $Param;
    private static string $Acc_State;

    public static function set(CurrentUserDTO $curr):void{
        CurrUser::$Id = $curr->getId();
        CurrUser::$Role = $curr->getRole();
        CurrUser::$Param = $curr->getParam();
        CurrUser::$Acc_State = $curr->getAcc_State();
        return;
    }

    public static function getId():int{
        if(isset(CurrUser::$Id)){
            return CurrUser::$Id;
        }
        else{
            throw new \Exception("There is no Id set");
        }
    }

    public static function getRole():string{
        if(isset(CurrUser::$Role)){
            return CurrUser::$Role;
        }
        else{
            throw new \Exception("There is no Role set");
        }
    }

    public static function getParam():bool{
        if(isset(CurrUser::$Param)){
            return CurrUser::$Param;
        }
        else{
            throw new \Exception("There is no Param set");
        }
    }

    public static function getAcc_State():string{
        if(isset(CurrUser::$Acc_State)){
            return CurrUser::$Acc_State;
        }
        else{
            throw new \Exception("There is no Acc_State set");
        }
    }
}
