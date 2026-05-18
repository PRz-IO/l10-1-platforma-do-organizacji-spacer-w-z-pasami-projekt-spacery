<?php

namespace App\DTOs;

class ChangePasswordDTO
{
    protected int $Id;
    protected string $Password;
    public function __construct(int $Id, string $Password)
    {
        $this->Id = $Id;
        $this->Password = $Password;
    }
    public function getId():int{
        return $this->Id;
    }
    public function setId(int $Id){
        $this->Id = $Id;
    }

    public function getPassword():string{
        return $this->Password;
    }
    public function setPassword(string $Password){
        $this->Password = $Password;
    }
}
