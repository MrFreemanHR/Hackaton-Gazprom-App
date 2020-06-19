<?php

namespace App\Entity;

use App\Repository\DivisionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DivisionRepository::class)
 */
class Division
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $manager;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $employee = [];

    /**
     * @ORM\Column(type="json")
     */
    private $tickets = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $owner;

    public function getId(): ?int
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

    public function getManager(): ?int
    {
        return $this->manager;
    }

    public function setManager(int $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

    public function getEmployee(): ?array
    {
        return $this->employee;
    }

    public function setEmployee(?array $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    public function getTickets(): ?array
    {
        return $this->tickets;
    }

    public function setTickets(array $tickets): self
    {
        $this->tickets = $tickets;

        return $this;
    }

    public function getOwner(): ?int
    {
        return $this->owner;
    }

    public function setOwner(int $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
