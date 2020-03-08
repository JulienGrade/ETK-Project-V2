<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Cocur\Slugify\Slugify;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Cet email est déjà enregistré sur notre site !"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez renseigner votre prénom")
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Le prénom doit faire au moins {{ limit }} caractères de long",
     *      maxMessage = "Le prénom ne peut pas faire plus de {{ limit }} caractères"
     * )
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Le prénom ne peut contenir de numéro"
     * )
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez renseigner votre nom de famille")
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Le nom doit faire au moins {{ limit }} caractères de long",
     *      maxMessage = "Le nom ne peut pas faire plus de {{ limit }} caractères"
     * )
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Le nom ne peut contenir de numéro"
     * )
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Veuillez renseigner un email valide")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex("/^(?=.*[A-z])(?=.*[A-Z])(?=.*[0-9])\S{6,12}$/",
     *     message="Votre mot de passe doit contenir des caractères dont au moins 1 en majscule et un chiffre")
     */
    private $hash;

    /**
     * Ici on s'occupe du champs de confirmation
     *
     * @Assert\EqualTo(propertyPath="hash", message="Vous n'avez pas correctement confirmé votre mot de passe !")
     */
    public $passwordConfirm;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups={"admin"})
     * @Assert\Length(
     *      min = 2,
     *      max = 60,
     *      minMessage = "La ville doit faire au moins {{ limit }} caractères de long",
     *      maxMessage = "La ville ne peut pas faire plus de {{ limit }} caractères"
     * )
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="La ville ne peut contenir de numéro"
     * )
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups={"admin"})
     * @Assert\Regex("/[0-9]{10}/")
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $passwordRequestedAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", mappedBy="users")
     */
    private $userRoles;

    /**
     * @ORM\OneToMany(targetEntity="Booking", mappedBy="booker")
     */
    private $bookings;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WaitList", mappedBy="user")
     */
    private $waitLists;

    private $cgu;


    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->waitLists = new ArrayCollection();
    }

    /**
     * Ici on gère la date de création de la réservation
     *
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function prePersist(){
        if(empty($this->createdAt)) {
            $this->createdAt = new \Datetime();
        }
    }

    /**
     * Permet de creer un nom complet prenom+nom
     */
    public function getFullName(){
        return "{$this->firstName} {$this->lastName}";
    }

    /**
     * Permet d'initialiser le slug
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function initializeSlug()
    {
        if(empty($this->slug))  {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->firstName. ' ' .$this->lastName);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        // On transforme les roles en tableau de role grace a toArray
        // puis en tableau de chaine de caractere grace a map
        $roles = $this->userRoles->map(function($role){
            return $role->getTitle();
        })->toArray();

        // Ici on ajoute le role user a tous les utilisateurs
        $roles[] = 'ROLE_USER';

        return $roles;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->hash;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getPasswordRequestedAt(): ?\DateTimeInterface
    {
        return $this->passwordRequestedAt;
    }

    public function setPasswordRequestedAt(?\DateTimeInterface $passwordRequestedAt): self
    {
        $this->passwordRequestedAt = $passwordRequestedAt;

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(Role $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
            $userRole->addUser($this);
        }

        return $this;
    }

    public function removeUserRole(Role $userRole): self
    {
        if ($this->userRoles->contains($userRole)) {
            $this->userRoles->removeElement($userRole);
            $userRole->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setBooker($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->contains($booking)) {
            $this->bookings->removeElement($booking);
            // set the owning side to null (unless already changed)
            if ($booking->getBooker() === $this) {
                $booking->setBooker(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|WaitList[]
     */
    public function getWaitLists(): Collection
    {
        return $this->waitLists;
    }

    public function addWaitList(WaitList $waitList): self
    {
        if (!$this->waitLists->contains($waitList)) {
            $this->waitLists[] = $waitList;
            $waitList->setUser($this);
        }

        return $this;
    }

    public function removeWaitList(WaitList $waitList): self
    {
        if ($this->waitLists->contains($waitList)) {
            $this->waitLists->removeElement($waitList);
            // set the owning side to null (unless already changed)
            if ($waitList->getUser() === $this) {
                $waitList->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCgu()
    {
        return $this->cgu;
    }

    /**
     * @param mixed $cgu
     */
    public function setCgu($cgu)
    {
        $this->cgu = $cgu;
    }

}
