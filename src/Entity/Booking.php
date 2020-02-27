<?php

namespace App\Entity;

use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingsRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields= {"event", "booker"},
 *     message="Vous avez déjà réservé pour cet événement !"
 * )
 *
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(min=10, minMessage="Votre commentaire concernant la réservation doit faire au moins 10 caractères", max="500", maxMessage="Votre commentaire concernant la réservation ne peut excéder 500 caractères !")
     */
    private $comment;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Children", mappedBy="booking", orphanRemoval=true,cascade={"persist"})
     * @Assert\Valid()
     * @Assert\NotBlank(message="Vous devez inscrire au moins un enfant de l'age recquit pour l'événement")
     *
     */
    private $childrens;


    public function __construct()
    {
        $this->childrens = new ArrayCollection();
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

    // Permet de verifier que l'age des enfants correspond à l'age de l'événement


    /**
     *
     * @Assert\Callback(groups={"Edit"})
     * @param ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context){
        $event=$this->getEvent();
        $ageMin = $event->getAgeMin();
        $ageMax = $event->getAgeMax();
        $childrens= $this->getChildrens();
        foreach ($childrens as $children){
            $childrenAge = $children->getAge();
            if($childrenAge > $ageMax || $childrenAge < $ageMin){
                $context->buildViolation('L\'age saisi ne correspond pas à l\'évenement')
                    ->atPath('childrens')
                    ->addViolation();
            }
        }


    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection|Children[]
     */
    public function getChildrens(): Collection
    {
        return $this->childrens;
    }

    public function addChildren(Children $children): self
    {
        if (!$this->childrens->contains($children)) {
            $this->childrens[] = $children;
            $children->setBooking($this);
        }

        return $this;
    }

    public function removeChildren(Children $children): self
    {
        if ($this->childrens->contains($children)) {
            $this->childrens->removeElement($children);
            // set the owning side to null (unless already changed)
            if ($children->getBooking() === $this) {
                $children->setBooking(null);
            }
        }

        return $this;
    }

}
