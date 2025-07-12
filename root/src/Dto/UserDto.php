<?php

namespace App\Dto;

readonly final class UserDto
{
    public int $id;
    public string $email;
    public function __construct(int $id, string $email)
    {
        $this->id = $id;
        $this->email = $email;
    }
}