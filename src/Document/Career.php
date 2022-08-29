<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @MongoDB\Document
 */
class Career
{
    /**
     * @MongoDB\Id
     */
    #[SerializedName('id')]
    protected string $id;

    /**
     * @MongoDB\Field(type="string")
     */
    #[SerializedName('name')]
    private string $name;

    public function getId(): string
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }



    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
