<?php

namespace App\DTOs;

class LogInDTO
{
    protected string $Login;

    protected string $Password;

    public function __construct(string $Login, string $Password)
    {
        $this->Login = $Login;
        $this->Password = $Password;
    }

    public function getLogin(): string
    {
        return $this->Login;
    }

    public function setLogin(string $Login)
    {
        $this->Login = $Login;
    }

    public function getPassword(): string
    {
        return $this->Password;
    }

    public function setPassword(string $Password)
    {
        $this->Password = $Password;
    }
}
