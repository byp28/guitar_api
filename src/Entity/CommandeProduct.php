<?php

namespace App\Entity;

use App\Repository\CommandeProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeProductRepository::class)]
class CommandeProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'commandeProducts')]
    private ?Commande $Commande_id = null;

    #[ORM\ManyToOne(inversedBy: 'commandeProducts')]
    private ?Product $Product_id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommandeId(): ?Commande
    {
        return $this->Commande_id;
    }

    public function setCommandeId(?Commande $Commande_id): static
    {
        $this->Commande_id = $Commande_id;

        return $this;
    }

    public function getProductId(): ?Product
    {
        return $this->Product_id;
    }

    public function setProductId(?Product $Product_id): static
    {
        $this->Product_id = $Product_id;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }
}
