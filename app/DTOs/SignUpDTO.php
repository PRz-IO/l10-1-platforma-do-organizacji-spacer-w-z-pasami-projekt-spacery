<?php

namespace App\DTOs;

class SignUpDTO extends LogInDTO
{
    protected string $Name;
    protected string $Last_Name;
    protected string $Email;
    protected string $Phone_Num;
    protected string $Role;
    public function __construct(
        string $Login, string $Password, 
        string $Name, string $Last_Name, 
        string $Email, string $Phone_Num,
        string $Role
        )
    {
        $this->Login = $Login;
        $this->Password = $Password;
        $this->Name = $Name;
        $this->Last_Name = $Last_Name;
        $this->Email = $Email;
        $this->Phone_Num = $Phone_Num;
        $this->Role = $Role;
        
    }

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

    public function getRole():string{
        return $this->Role;
    }
    public function setRole(string $Role){
        $this->Role = $Role;
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
