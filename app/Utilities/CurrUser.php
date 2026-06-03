<?php

namespace App\Utilities;

use App\DTOs\CurrentUserDTO;

class CurrUser
{
    public static function set(CurrentUserDTO $curr):void{
        session(['Id'=>$curr->getId()]);
        session(['Role'=>$curr->getRole()]);
        session(['Param'=>$curr->getParam()]);
        session(['AccState'=>$curr->getAcc_State()]);
        session(['IsLogged'=>True]);
        return;
    }
    public static function LogOut():void{
        session()->forget(['Id','Role','Param','AccState']);
        session(['IsLogged'=>False]);
        return;
    }
    public static function IsLogged():bool{
        return session('IsLogged',False);
    }
    public static function getId():int{
        if(session()->has("Id")){
            return session("Id");
        }
        else{
            throw new \Exception("There is no Id set");
        }
    }

    public static function getRole():string{
        if(session()->has("Role")){
            return session("Role");
        }
        else{
            throw new \Exception("There is no Role set");
        }
    }

    public static function getParam():bool{
        if(session()->has("Param")){
            return session('Param');
        }
        else{
            throw new \Exception("There is no Param set");
        }
    }

    public static function getAcc_State():string{
        if(session()->has('AccState')){
            return session('AccState');
        }
        else{
            throw new \Exception("There is no Acc_State set");
        }
    }
}
