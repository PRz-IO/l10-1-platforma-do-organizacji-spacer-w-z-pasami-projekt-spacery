<?php

namespace App\DTOs;

class ProfileDTO
{
    protected string $Name;
    protected string $Last_Name;
    protected string $Login;
    protected string $Creation_Date;
    protected string $Email;
    protected string $Phone_Num;
    protected array $History;
    /*
    public function __construct(
        string $Name,string $Last_Name,
        string $Login,string $Creation_Date,
        array $History
        )
    {
        $this->Name = $Name;
        $this->Last_Name = $Last_Name;
        $this->Login = $Login;
        $this->Creation_Date = $Creation_Date;
        $this->History = $History;
    }
    */
    public function getName():string{
        return $this->Name;
    }
    public function setName(string $Name){
        $this->Name = $Name;
    }

    public function getLast_Name():string{
        return $this->Last_Name;
    }
    public function setLast_Name(string $Last_Name){
        $this->Last_Name = $Last_Name;
    }

    public function getLogin():string{
        return $this->Login;
    }
    public function setLogin(string $Login){
        $this->Login = $Login;
    }

    public function getCreation_Date():string{
        return $this->Creation_Date;
    }
    public function setCreation_Date(string $Creation_Date){
        $this->Creation_Date = $Creation_Date;
    }

    public function getHistory():array{
        return $this->History;
    }
    public function setHistory(array $History){
        $this->History = $History;
    }

    public function getEmail():string{
        return $this->Email;
    }
    public function setEmail(string $Email){
        $this->Login = $Email;
    }

    public function getPhone_Num():string{
        return $this->Phone_Num;
    }
    public function setPhone_Num(string $Phone_Num){
        $this->Login = $Phone_Num;
    }
}
