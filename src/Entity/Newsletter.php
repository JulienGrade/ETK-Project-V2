<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


class Newsletter
{

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex("#^[a-zA-Z-]+@[a-zA-Z-]+\.[a-zA-Z]{2,6}$#")
     */
    private $email;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
