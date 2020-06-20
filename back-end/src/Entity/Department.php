<?php

namespace App\Entity;

use App\Repository\DepartmentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DepartmentRepository::class)
 */
class Department
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
     * @ORM\Column(type="json", nullable=true)
     */
    private $division = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $manager;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $managment = [
        "opened"    => [],
        "closed"    => []
    ];

    /**
     * @ORM\Column(type="json")
     */
    private $tickets = [];

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

    public function getDivision(): ?array
    {
        return $this->division;
    }

    public function setDivision(?array $division): self
    {
        $this->division = $division;

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

    public function getManagment(): ?array
    {
        return $this->managment;
    }

    public function setManagment(?array $managment): self
    {
        $this->managment = $managment;

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
}
