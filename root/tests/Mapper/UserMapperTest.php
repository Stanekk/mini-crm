<?php

namespace App\Tests\Mapper;

use App\Dto\UserDto;
use App\Entity\User;
use App\Mapper\UserMapper;
use PHPUnit\Framework\TestCase;

class UserMapperTest extends TestCase
{
    public function testToDto(): void
    {
        $user = new User();
        $reflection = new \ReflectionClass(User::class);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($user, 123);
        $user->setEmail('email@example.com');

        $mapper = new UserMapper();
        $dto = $mapper->toDto($user);

        $this->assertInstanceOf(UserDto::class, $dto);
        $this->assertEquals(123, $dto->id);
        $this->assertEquals('email@example.com', $dto->email);

    }
}