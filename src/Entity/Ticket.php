<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AcmeAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketRepository")
 */
class Ticket
{
    const PRICE_TYPE_REDUCTION = 1;
    const PRICE_TYPE_BABY = 2;
    const PRICE_TYPE_CHILD = 3;
    const PRICE_TYPE_NORMAL = 4;
    const PRICE_TYPE_SENIOR = 5;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=2,
     *      max=255,
     *     minMessage="Votre nom est trop court!",
     *     maxMessage="Votre nom est trop long!"
     * )
     *
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * @ORM\Column(type="integer")
     */
    private $priceType;

    /**
     * @ORM\Column(type="date")
     * @AcmeAssert\OpeningDate
     */
    private $visitAt;

    /**
     * @ORM\Column(type="date")
     */
    private $birthDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Command", inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $command;

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getPriceType(): ?int
    {
        return $this->priceType;
    }

    public function setPriceType(int $priceType): self
    {
        $this->priceType = $priceType;

        return $this;
    }

    public function getVisitAt(): ?\DateTimeInterface
    {
        return $this->visitAt;
    }

    public function setVisitAt(\DateTimeInterface $visitAt): self
    {
        $this->visitAt = $visitAt;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getCommand(): Command
    {
        return $this->command;
    }

    public function setCommand(Command $command): self
    {
        $this->command = $command;

        return $this;
    }
}
