<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Entitat User - Representa un usuari del mercat de segona mà.
 * Implementa UserInterface per integrar-se amb el sistema de seguretat de Symfony.
 * Té una relació OneToMany amb Product (un usuari pot tenir molts productes).
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'Ja existeix un compte amb aquest email.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Email de l'usuari - utilitzat com a identificador únic per al login.
     */
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    /**
     * Rols de l'usuari - emmagatzemats com a JSON.
     * Per defecte tots els usuaris tenen ROLE_USER.
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * Contrasenya hashejada de l'usuari.
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * Nom visible de l'usuari.
     */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * Col·lecció de productes publicats per l'usuari.
     * Relació OneToMany: un usuari pot tenir molts productes.
     * orphanRemoval=true: si s'elimina un producte de la col·lecció, s'esborra de la BD.
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Product::class, orphanRemoval: true)]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * Identificador visual de l'usuari per al sistema de seguretat.
     * En aquest cas, l'email serveix com a identificador únic.
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // Tots els usuaris tenen com a mínim ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
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

    /**
     * Mètode requerit per UserInterface.
     * Neteja dades sensibles temporals (per exemple, plainPassword).
     */
    public function eraseCredentials(): void
    {
        // Si emmagatzéssim dades temporals sensibles, les netejaríem aquí
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setOwner($this);
        }
        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            if ($product->getOwner() === $this) {
                $product->setOwner(null);
            }
        }
        return $this;
    }
}
