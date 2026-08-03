<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Override;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["user.index"])]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Groups(["user.index", "user.create", 'user.edit'])]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    #[Groups(["user.index", "user.create", 'user.edit'])]
    private ?string $email = null;

    #[ORM\Column(length: 100)]
    #[Groups(["user.create",'user.edit'])]
    #[Assert\Length(min : 8, minMessage:"Mot de passe trop court")]
    private ?string $password = null;

    #[ORM\ManyToOne(inversedBy: 'adresse')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Adresse $adresses = null;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'user')]
    private Collection $user;

    /**
     * @var Collection<int, Avis>
     */
    #[ORM\OneToMany(targetEntity: Avis::class, mappedBy: 'user')]
    private Collection $avis;

    #[ORM\Column(length: 10)]
    #[Groups(["user.index"])]
    private ?string $type = null;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'user')]
    private Collection $commandes;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->avis = new ArrayCollection();
        $this->commandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getAdresses(): ?Adresse
    {
        return $this->adresses;
    }

    public function setAdresses(?Adresse $adresses): static
    {
        $this->adresses = $adresses;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(Commande $user): static
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
            $user->setUser($this);
        }

        return $this;
    }

    public function removeUser(Commande $user): static
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getUser() === $this) {
                $user->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Avis>
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): static
    {
        if (!$this->avis->contains($avi)) {
            $this->avis->add($avi);
            $avi->setUser($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): static
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getUser() === $this) {
                $avi->setUser(null);
            }
        }

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    #[Override]
    public function getRoles(): array
    {
        return match ($this->type) {
            'Admin' => ['ROLE_ADMIN'],
            'Client' => ['ROLE_CLIENT'],
            'Employe' => ['ROLE_EMPLOYE'],
            default => ['ROLE_USER'],
        };

    }


public function getUserIdentifier(): string
    {
        return $this->email;
    }



    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setUser($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getUser() === $this) {
                $commande->setUser(null);
            }
        }

        return $this;
    }
}
