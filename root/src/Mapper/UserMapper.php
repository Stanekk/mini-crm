<?php

namespace App\Mapper;

use App\Dto\UserDto;
use App\Entity\User;

class UserMapper
{
    public function toDto(User $user): UserDto
    {
        return new UserDto(
            $user->getId(),
            $user->getEmail(),
        );
    }
}
