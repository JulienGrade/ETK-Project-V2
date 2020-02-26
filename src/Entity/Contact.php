<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Contact
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=50, minMessage="Votre prénom doit comporter au moins 2 caractères", maxMessage="Votre prénom doit comporter au maximum 50 caractères")
     */
    private $firstName;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=50, minMessage="votre nom doit comporter au moins 2 caractères", maxMessage="Votre nom ne peut comporter plus de 50 caractères")
     */
    private $lastName;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Regex("/[0-9]{10}/", message="Votre numéro de téléphone ne doit comporter que des chiffres")
     */
    private $phone;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email(message="Veuillez saisir un email valide !")
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=10, minMessage="Votre message doit comporter au moins 10 caractères")
     */
    private $message;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $genre;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $comeFrom;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $town;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=50)
     */
    private $yourTown;

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }



    /**
     * @param string $firstName
     * @return Contact
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return Contact
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return Contact
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Contact
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return Contact
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * @param string $genre
     * @return Contact
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;
        return $this;
    }

    /**
     * @return string
     */
    public function getComeFrom()
    {
        return $this->comeFrom;
    }

    /**
     * @param string $comeFrom
     * @return Contact
     */
    public function setComeFrom($comeFrom)
    {
        $this->comeFrom = $comeFrom;
        return $this;
    }

    /**
     * @return string
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * @param string $town
     * @return Contact
     */
    public function setTown($town)
    {
        $this->town = $town;
        return $this;
    }

    /**
     * @return string
     */
    public function getYourTown()
    {
        return $this->yourTown;
    }

    /**
     * @param string $yourTown
     * @return Contact
     */
    public function setYourTown(string $yourTown)
    {
        $this->yourTown = $yourTown;
        return $this;
    }



}