<?php

namespace App\DTOs;

class WProfileDTO extends ProfileDTO
{
    protected bool $Is_Admin;
    public function __construct(
        string $Name,string $Last_Name,
        string $Login,string $Creation_Date,
        string $Email, string $Phone_Num,
        array $History,bool $Is_Admin
        )
    {
        $this->Name = $Name;
        $this->Last_Name = $Last_Name;
        $this->Login = $Login;
        $this->Creation_Date = $Creation_Date;
        $this->Email = $Email;
        $this->Phone_Num = $Phone_Num;
        $this->History = $History;
        $this->Is_Admin = $Is_Admin;
    }

    public function getIs_Admin():bool{
        return $this->Is_Admin;
    }
    public function setIs_Admin(bool $Is_Admin){
        $this->Is_Admin = $Is_Admin;
    }
}
