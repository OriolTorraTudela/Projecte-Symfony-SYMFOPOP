<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Entitat Product - Representa un producte del mercat de segona mà.
 * Cada producte pertany a un usuari (owner) mitjançant una relació ManyToOne.
 */
#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Títol del producte - obligatori, entre 3 i 255 caràcters.
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'El títol no pot estar buit.')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'El títol ha de tenir com a mínim {{ limit }} caràcters.',
        maxMessage: 'El títol no pot superar els {{ limit }} caràcters.'
    )]
    private ?string $title = null;

    /**
     * Descripció del producte - obligatòria, mínim 10 caràcters.
     */
    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'La descripció no pot estar buida.')]
    #[Assert\Length(
        min: 10,
        minMessage: 'La descripció ha de tenir com a mínim {{ limit }} caràcters.'
    )]
    private ?string $description = null;

    /**
     * Preu del producte - obligatori, ha de ser positiu.
     * Tipus decimal amb precisió 10 i escala 2 (fins a 99999999.99).
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Assert\NotBlank(message: 'El preu no pot estar buit.')]
    #[Assert\Positive(message: 'El preu ha de ser un valor positiu.')]
    private ?string $price = null;

    /**
     * URL de la imatge del producte - camp opcional.
     * Si no s'especifica, es generarà automàticament amb Picsum.
     */
    #[ORM\Column(length: 500, nullable: true)]
    #[Assert\Url(message: 'La URL de la imatge no és vàlida.')]
    private ?string $image = null;

    /**
     * Data de creació del producte - s'assigna automàticament al crear.
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    /**
     * Propietari del producte - relació ManyToOne amb User.
     * Molts productes poden pertànyer a un sol usuari.
     */
    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;
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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;
        return $this;
    }
}
