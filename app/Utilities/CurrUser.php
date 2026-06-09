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

    // --- TUTAJ SĄ BEZPIECZNE POPRAWKI ---

    public static function getId():int{
        // Jeśli nie ma Id w sesji, zwróci bezpieczne 0 (typ int się zgadza)
        return session("Id", 0); 
    }

    public static function getRole():string{
        // Jeśli nie ma roli, zwróci pusty string (typ string się zgadza, brak crashu)
        return session("Role", ''); 
    }

    public static function getParam():bool{
        // Jeśli nie ma parametru, zwróci false (typ bool się zgadza)
        return session('Param', false); 
    }

    public static function getAcc_State():string{
        // Jeśli nie ma stanu konta, zwróci pusty string
        return session('AccState', ''); 
    }
}