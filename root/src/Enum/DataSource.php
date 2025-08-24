<?php

namespace App\Enum;

enum DataSource: string
{
    case Faker = 'faker';
    case App = 'app';
}
