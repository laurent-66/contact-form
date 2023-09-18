<?php

namespace App\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ContactRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message: "La valeur ne peut être vide."
    )]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message: "La valeur ne peut être vide."
    )]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message: "La valeur ne peut être vide."
    )]
    #[Assert\Email(
        message: 'l\'email {{ value }} n\'est pas un email valide.',
    )]
    private ?string $email = null;

    #[ORM\Column]
    private ?bool $webmaster = false;

    #[Assert\NotBlank(
        message: "La question ne peut être vide."
    )]
    private string $comment;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'contact', targetEntity: RequestContact::class)]
    private Collection $requestContacts;

    public function __construct()
    {
        $this->requestContacts = new ArrayCollection();
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function isWebmaster(): ?bool
    {
        return $this->webmaster;
    }

    public function setWebmaster(bool $webmaster): static
    {
        $this->webmaster = $webmaster;

        return $this;
    }


    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, RequestContact>
     */
    public function getRequestContacts(): Collection
    {
        return $this->requestContacts;
    }

    public function addRequestContact(RequestContact $requestContact): static
    {
        if (!$this->requestContacts->contains($requestContact)) {
            $this->requestContacts->add($requestContact);
            $requestContact->setContact($this);
        }

        return $this;
    }

    public function removeRequestContact(RequestContact $requestContact): static
    {
        if ($this->requestContacts->removeElement($requestContact)) {
            // set the owning side to null (unless already changed)
            if ($requestContact->getContact() === $this) {
                $requestContact->setContact(null);
            }
        }

        return $this;
    }


    public function getContentObject() {
       $arrayObject = get_object_vars($this);
       array_shift($arrayObject);
        $i = 1;
        while ($i <= 4):
            array_pop($arrayObject);
            $i++;
        endwhile;
        $arrayPropertiesRequest = $this->arrayPropertiesRequest();
        $arrayContentObject = [...$arrayObject, ...$arrayPropertiesRequest];

       return  $arrayContentObject;
    }


    public function arrayPropertiesRequest()
    {
        $arrayObject = get_object_vars($this);
        $arrayRequestObject = array_pop($arrayObject);
        $arraylength = count($arrayRequestObject)-1;
        $RequestObject = $arrayRequestObject[$arraylength];
        $arrayPropertiesRequest = [
            'question'=> $RequestObject->getContentText(),
            'date_question'=> $RequestObject->getCreatedAt(),
        ];

        return $arrayPropertiesRequest;
    }

}
