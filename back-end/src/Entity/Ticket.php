<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TicketRepository::class)
 */
class Ticket
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
    private $theme;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $division;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $department;

    /**
     * @ORM\Column(type="text")
     */
    private $problem;

    /**
     * @ORM\Column(type="text")
     */
    private $answer;

    /**
     * @ORM\Column(type="text")
     */
    private $keywords;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $creator;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $votesFor = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $votesAgainst = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $state;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    public function getDivision(): ?int
    {
        return $this->division;
    }

    public function setDivision(?int $division): self
    {
        $this->division = $division;

        return $this;
    }

    public function getDepartment(): ?int
    {
        return $this->department;
    }

    public function setDepartment(?int $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getProblem(): ?string
    {
        return $this->problem;
    }

    public function setProblem(string $problem): self
    {
        $this->problem = $problem;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(string $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function getCreator(): ?string
    {
        return $this->creator;
    }

    public function setCreator(string $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getVotesFor(): ?array
    {
        return $this->votesFor;
    }

    public function setVotesFor(array $votesFor): self
    {
        $this->votesFor = $votesFor;

        return $this;
    }

    public function getVotesAgainst(): ?array
    {
        return $this->votesAgainst;
    }

    public function setVotesAgainst(?array $votesAgainst): self
    {
        $this->votesAgainst = $votesAgainst;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getAll(): array
    {
        return [
            'theme'     => $this->theme,
            'problem'   => $this->problem,
            'answer'    => $this->answer,
            'keywords'  => $this->keywords,
            'creator'   => $this->creator,
            'votesFor'  => count($this->votesFor),
            'votesAgainst' => count($this->votesAgainst),
            'state'     => $this->state,
            'id'        => $this->id,
        ];
    }
}
