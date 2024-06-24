<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Product|null find($id, $lockMode = null, $loadData = true)
 * @method Product|null findOneBy($criteria, $orderBy = null, $limit = null, $offset = null)
 * @method Product[]    findAll($orderBy = null, $limit = null, $offset = null)
 * @method Product[]    findBy($criteria, $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    // Custom methods for Product entity
}