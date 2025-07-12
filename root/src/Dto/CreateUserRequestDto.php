<?php

namespace App\Dto;
use App\Validator\Constraints\EqualTwoStrings;
use App\Validator\Constraints\UniqueUserEmail;
use Symfony\Component\Validator\Constraints as Assert;
#[EqualTwoStrings(field: 'password', fieldToCompare: 'passwordConfirm', message: 'Passwords do not match')]
final class CreateUserRequestDto
{
    #[Assert\Email(message: 'Please provide a valid email address.')]
    #[UniqueUserEmail]
    public ?string $email;
    #[Assert\Length(
        min: 5,
        minMessage: 'A password should be a leats {{ limit }} characters long',
    )]

    public string $password;
    public string $passwordConfirm;

    public function __construct(
        string $email = null,
        string $password = null,
        string $passwordConfirm = null
    )
    {
        $this->email = $email;
        $this->password = $password;
        $this->passwordConfirm = $passwordConfirm;
    }
}