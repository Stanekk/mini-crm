<?php

namespace App\Entity;

use App\Enum\DataSource;
use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
abstract class BaseEntity
{
    #[ORM\Column(type: 'string', enumType: DataSource::class)]
    protected DataSource $source;

    public function __construct()
    {
        $this->source = DataSource::App;
    }

    public function getSource(): DataSource
    {
        return $this->source;
    }

    public function setSource(DataSource $source): void
    {
        $this->source = $source;
    }
}
