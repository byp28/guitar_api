<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_cmd = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_exp = null;

    #[ORM\ManyToOne(inversedBy: 'user')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCmd(): ?\DateTime
    {
        return $this->date_cmd;
    }

    public function setDateCmd(\DateTime $date_cmd): static
    {
        $this->date_cmd = $date_cmd;

        return $this;
    }

    public function getDateExp(): ?\DateTime
    {
        return $this->date_exp;
    }

    public function setDateExp(\DateTime $date_exp): static
    {
        $this->date_exp = $date_exp;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
