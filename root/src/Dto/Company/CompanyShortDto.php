<?php

namespace App\Dto\Company;

use App\Enum\DataSource;

final readonly class CompanyShortDto
{
    public int $id;
    public string $name;
    public string $email;
    public DataSource $source;

    public function __construct(int $id, string $name, string $email, DataSource $source)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->source = $source;
    }
}
