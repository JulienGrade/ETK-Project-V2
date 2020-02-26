<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;


class PasswordUpdate
{



    private $oldPassword;

    /**
     * @Assert\Length(min=6,minMessage="Votre mot de passe doit faire au moins 6 caractères !")
     * @Assert\Regex("/^(?=.*[A-z])(?=.*[A-Z])(?=.*[0-9])\S{6,12}$/",
     *     message="Votre mot de passe doit contenir des caractères dont au moins 1 en majscule et un chiffre")
     */
    private $newPassword;


    /**
     * @Assert\EqualTo(propertyPath="newPassword", message="Vous n'avez pas correctement confirmé votre nouveau mot de passe")
     */
    private $confirmPassword;



    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }
}