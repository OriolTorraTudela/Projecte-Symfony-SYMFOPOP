<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repositori per a l'entitat Product.
 * Proporciona mètodes per consultar productes a la base de dades.
 *
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Retorna tots els productes ordenats per data de creació (més recents primer).
     *
     * @return Product[]
     */
    public function findAllOrderedByDate(): array
    {
        return $this->findBy([], ['createdAt' => 'DESC']);
    }

    /**
     * Retorna els productes d'un usuari concret, ordenats per data de creació.
     *
     * @return Product[]
     */
    public function findByOwnerOrderedByDate(int $ownerId): array
    {
        return $this->findBy(
            ['owner' => $ownerId],
            ['createdAt' => 'DESC']
        );
    }
}
