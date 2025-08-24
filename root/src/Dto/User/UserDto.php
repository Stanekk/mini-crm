<?php

namespace App\Dto\User;

use App\Enum\DataSource;

final readonly class UserDto
{
    public int $id;
    public string $email;
    public array $roles;
    public DataSource $source;

    public function __construct(int $id, string $email, array $roles, DataSource $source)
    {
        $this->id = $id;
        $this->email = $email;
        $this->roles = $roles;
        $this->source = $source;
    }
}
