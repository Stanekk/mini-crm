<?php

namespace App\Dto\User;

final readonly class UserDto
{
    public int $id;
    public string $email;
    public array $roles;

    public function __construct(int $id, string $email, array $roles)
    {
        $this->id = $id;
        $this->email = $email;
        $this->roles = $roles;
    }
}
